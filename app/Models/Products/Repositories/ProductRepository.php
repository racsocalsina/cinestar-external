<?php


namespace App\Models\Products\Repositories;


use App\Enums\GlobalEnum;
use App\Enums\PurchaseStatus;
use App\Helpers\EloquentHelper;
use App\Helpers\FileHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use App\Models\Customers\Customer;
use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Products\Product;
use App\Models\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\ProductTypes\ProductType;
use App\Models\PurchaseItems\PurchaseItem;
use App\SearchableRules\ProductSearchableRule;
use App\Services\Searchable\Searchable;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Product $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function sync($body, $headquarter = null)
    {
        if ($headquarter == null)
            $headquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];
        $product = Product::where('code', $data['code'])->first();
        $productType = ProductType::where('code', $data['artlin'])->first();

        if ($action === GlobalEnum::ACTION_SYNC_INSERT || $action === GlobalEnum::ACTION_SYNC_UPDATE || $action === GlobalEnum::ACTION_SYNC_IMPORT) {
            // Product
            $arrayBody = [
                'code' => $data['code'],
                'name' => $data['name'],
                'name_abbr' => $data['name_abbr'],
                'product_type_id' => $productType ? $productType->id : null,
                'is_available' => $data['available'],
                'is_combo' => FunctionHelper::checkIfProductSyncIsCombo($data['artlin']),
                'order' => FunctionHelper::IsNullOrEmptyString($data['order']) ? null : $data['order']
            ];

            if ($product) {
                $product->update($arrayBody);
            } else {
                $product = Product::create($arrayBody);
            }

            // Detail
            $arrayBody = [
                'headquarter_id' => $headquarter->id,
                'product_id' => $product->id,
                'active' => $data['active'],
                'igv' => $data['igv'],
                'isc' => $data['isc'],
                'stock' => $data['stock'],
                'sales_unit' => $data['sales_unit'],
                'is_presale' => $data['is_presale'],
                'presale_start' => $data['presale_start'],
                'presale_end' => $data['presale_end']
            ];

            $headquarterProduct = HeadquarterProduct::where('headquarter_id', $headquarter->id)
                ->where('product_id', $product->id)->first();

            if ($headquarterProduct) {
                $headquarterProduct->update($arrayBody);
            } else {
                HeadquarterProduct::create($arrayBody);
            }
        }

        return null;
    }

    public function searchBo(array $params)
    {
        $params['is_available'] = true;
        EloquentHelper::addHeadquarterFilterByAdminRole('headquarter_id', $params, 'param');

        $query = $this->queryable();
        $query->with(['type']);
        $this->searchableService->applyArray($query, new ProductSearchableRule(), $params);
        $query->orderBy('name');
        return $query->paginate(Helper::perPage($params));
    }

    public function searchApi(array $params)
    {
        $params['customer_id'] = Customer::where('user_id', $params['user_id'])->first()->id ?? 0;
     
        $query = $this->model->select([
            'products.id', 'products.name', 'products.image', 'products.image2', 'headquarter_product.price',
            'customer_product_favorite.id as favorite_id', 'products.product_type_id as type_id',
            'products.is_combo'
        ])
            ->join('headquarter_product', 'products.id', 'headquarter_product.product_id')
            ->leftJoin('customer_product_favorite', function ($join) use ($params) {
                $join->on('products.id', '=', 'customer_product_favorite.product_id');
                $join->on('customer_product_favorite.customer_id', '=', DB::Raw($params['customer_id']));
            })
            ->where('headquarter_product.headquarter_id', $params['headquarter_id'])
            ->where('headquarter_product.active', 1)
            ->where('headquarter_product.stock', '>', 0)
            ->whereNotNull('headquarter_product.price')
            ->where('is_available', true);

        $hoy = date('Y-m-d');

        if ($params['presale_date'] == $hoy) {
            #1. Incluir todos los productos que no tengan periodo de preventa (Inicio o fin sean nulos)
            #2. Incluir los productos que estén dentro del periodo de preventa (Check preventa y fecha dentro del rango)
            $query->where(function (Builder $queryPresale) use ($hoy) {
                $queryPresale->where(function (Builder $queryPresaleA) {
                    $queryPresaleA->where('headquarter_product.is_presale', false);
                });
                $queryPresale->orWhere(function (Builder $queryPresaleB) use ($hoy) {
                    $queryPresaleB->where('headquarter_product.is_presale', true);
                    $queryPresaleB->whereDate('headquarter_product.presale_start', '<=', $hoy);
                    $queryPresaleB->whereDate('headquarter_product.presale_end', '>=', $hoy);
                });
            });

        } else {
            #1. Incluir los productos que estén dentro del periodo de preventa (Check preventa y fecha dentro del rango)
                $query->where('headquarter_product.is_presale', true);
                $query->whereDate('headquarter_product.presale_start', '<=', $params['presale_date']);
                $query->whereDate('headquarter_product.presale_end', '>=',$params['presale_date']);
         
        }

        $this->searchableService->applyArray($query, new ProductSearchableRule(), $params);

        return $query
            ->orderBy('favorite_id', 'desc')
            ->orderByRaw("ifnull(products.order, 'z') asc")
            ->orderBy('products.name', 'asc')
            ->get();
    }

    public function searchApiPromotions($headquarterId, $movie_time_id, $product_type_id, $today)
    {

        if(!isset($today)){$today = Carbon::today();}
        else{$today = Carbon::parse($today);}
        if ($movie_time_id) {
            $movie_time = MovieTime::find($movie_time_id);
            $today = Carbon::parse($movie_time->date_start);
        }
        return ChocoPromotionProduct::whereHas('choco_promotion', function ($query) use ($headquarterId, $today) {
            $query->when($headquarterId, function ($query) use ($headquarterId) {
                    $query->where(function ($q) use ($headquarterId) {
                        $q->whereNull('headquarter_id')->orWhere('headquarter_id', $headquarterId);
                    });
                })->where(function ($query) use ($today) {
                    $date = $today->formatLocalized('%A');
                    if ($date == 'Sunday') {
                        $query->where('is_block_sunday', 0);
                    } else if ($date == 'Monday') {
                        $query->where('is_block_monday', 0);
                    } else if ($date == 'Tuesday') {
                        $query->where('is_block_tuesday', 0);
                    } else if ($date == 'Wednesday') {
                        $query->where('is_block_wednesday', 0);
                    } else if ($date == 'Thuerday') {
                        $query->where('is_block_thursday', 0);
                    } else if ($date == 'Friday') {
                        $query->where('is_block_friday', 0);
                    } else if ($date == 'Saturday') {
                        $query->where('is_block_saturday', 0);
                    }
                });
                
            $hoy = Carbon::today();
            if ($today == $hoy) {
                $query->where(function (Builder $queryPresale) use ($hoy) {
                    $queryPresale->where(function (Builder $queryPresaleA) use ($hoy) {
                        $queryPresaleA->where('is_presale', false);
                        $queryPresaleA->whereDate('start_date', '<=', $hoy);
                        $queryPresaleA->whereDate('end_date', '>=', $hoy);
                    });
                    $queryPresale->orWhere(function (Builder $queryPresaleB) use ($hoy) {
                        $queryPresaleB->where('is_presale', true);
                        $queryPresaleB->whereDate('start_date', '<=', $hoy);
                        $queryPresaleB->whereDate('end_date', '>=', $hoy);
                    });
                });
            } else {
                    $query->where('is_presale', true);
                    $query->whereDate('start_date', '<=', $today);
                    $query->whereDate('end_date', '>=',$today);
            }
        })->whereHas('product', function ($query) use ($headquarterId, $product_type_id) {
            $query->whereHas('headquarters', function ($query) use ($headquarterId) {
                $query->where('active', 1)->where('headquarter_id', $headquarterId);
            })->where('is_available', 1);

            if ($product_type_id){
                $query->where('product_type_id', $product_type_id);
            }
        })->with(['product.type'])
            ->get();
    }

    public function applicatePromotionPrice($headquarter_id, $product, $choco_promotion_product)
    {
        $headquarter_product = HeadquarterProduct::where('headquarter_id', $headquarter_id)->where('product_id', $product->id)->first();
        $price = $headquarter_product->price;
        if ($choco_promotion_product->price) {
            $price = $choco_promotion_product->price;
        }else{
            $price = $price - ( $price * ($choco_promotion_product->discount_rate / 100) );
        }
       return $price;

    }

    public function getAvailableHeadquarters(Product $product, $byCombo = false)
    {
        return HeadquarterProduct::selectRaw('headquarters.id, headquarters.name, sum(headquarter_product.stock) as stock, headquarter_product.active')
            ->join('products', 'headquarter_product.product_id', 'products.id')
            ->join('headquarters', 'headquarter_product.headquarter_id', 'headquarters.id')
            ->where('headquarter_product.product_id', $product->id)
            ->where('products.is_combo', $byCombo)
            ->groupBy(['headquarters.id', 'headquarters.name', 'headquarter_product.active'])
            ->orderBy('headquarters.name')
            ->get();
    }

    public function update($product, $request)
    {
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::PRODUCTS_FOLDER, $request->file('image'));
            $product->image = $file_name;
        } else {
            if ($request->image_r == 1) {
                $product->image = null;
            }
        }

        if ($request->has('image2')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::PRODUCTS_FOLDER, $request->file('image2'));
            $product->image2 = $file_name;
        } else {
            if ($request->image2_r == 1) {
                $product->image2 = null;
            }
        }

        $product->save();
        return $product;
    }

    public function search($params)
    {
        $query = $this->queryable();
        $this->searchableService->applyArray($query, new ProductSearchableRule(), $params);
        return $query->get();
    }

    public function getTotals($params)
    {
        $month = $params['month'];
        $year = $params['year'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $headquarter_id = $params['headquarter_id'];
        $product_id = $params['product_id'];
        $combo_id = $params['combo_id'];
        $start_schedule = $params['start_schedule'];
        $end_schedule = $params['end_schedule'];
        $is_presale = $params['is_presale'];

        $total = PurchaseItem::selectRaw('sum(paid_amount) as total_amount, count(*) as total_count')
            ->join('purchases', 'purchase_items.purchase_id', 'purchases.id')
            ->leftJoin('movie_times', 'purchases.movie_time_id', 'movie_times.id')
            ->leftJoin('movies', 'movie_times.movie_id', 'movies.id')
            ->leftJoin('sweets_sold', 'purchase_items.id', 'sweets_sold.purchase_item_id')
            ->when($month, function ($q) use ($month, $year) {
                $q->whereRaw('MONTH(purchases.created_at) =' . $month)
                ->whereRaw('YEAR(purchases.created_at) =' . $year);
            })
            ->when($start_date, function ($q) use ($start_date, $end_date) {
                $q->whereDate('purchases.created_at', '>=', $start_date)
                    ->whereDate('purchases.created_at', '<=', $end_date);
            })
            ->when($headquarter_id, function ($q) use ($headquarter_id) {
                $q->where('purchases.headquarter_id', $headquarter_id);
            })
            ->when($is_presale, function ($q) {
                $q->whereRaw('purchases.created_at < movies.premier_date');
                $q->whereNotNull('movie_time_id');
            })
            ->when($start_schedule, function ($q) use ($start_schedule) {
                $q->where('movie_times.time_start', '>=', $start_schedule);
            })
            ->when($end_schedule, function ($q) use ($end_schedule) {
                $q->where('movie_times.time_start', '<=', $end_schedule);
            })
            ->when($product_id, function ($q) use ($product_id) {
                $q->where('sweets_sold.sweet_id', $product_id);
            })
            ->whereNotNull('purchase_sweet_id')
            ->where('purchases.status', PurchaseStatus::COMPLETED)
            ->first();
        $total_remote = 0;
        if (!is_null($headquarter_id) && !$is_presale) {
            $headquarter = Headquarter::find($headquarter_id);
            $art = $product_id ? Product::find($product_id) : ($combo_id ? Combo::find($combo_id) : null);
            $data = [
                'month' => $month,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_schedule' => substr(str_replace(':', '', $start_schedule), 0, 4),
                'end_schedule' => substr(str_replace(':', '', $end_schedule), 0, 4),
                'art_code' => $art ? $art->code : null
            ];
            $internal = $this->getInternalTotals($data, $headquarter);
            $total_remote = $internal['total_remote'] ? intval($internal['total_remote']) : 0;
        }
        $total['total_amount'] = $total['total_amount'] ? $total['total_amount'] : 0;
        $total['total_products'] = $total['total_count'] + $total_remote;
        $total['total_remote'] = $total_remote;
        return $total;
    }

    public function getInternalTotals($data, $headquarter)
    {
        $token = Helper::loginInternal($headquarter);
        $api_url = Helper::addSlashToUrl($headquarter['api_url']);
        $client = new Client();
        $URL_GET_TOTALS = $api_url . "api/v1/consumer/purchase-sweet-totals";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $params['json'] = $data;
        $params['headers'] = $headers;
        $response = $client->post($URL_GET_TOTALS, $params);
        $body = (string)$response->getBody();
        $body = json_decode($body, true);
        return $body;
    }
}
