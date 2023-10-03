<?php


namespace App\Models\ProductTypes\Repositories;


use App\Enums\GlobalEnum;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use App\Models\HeadquarterProductTypes\HeadquarterProductType;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\ProductTypes\ProductType;
use App\Models\ProductTypes\Repositories\Interfaces\ProductTypeRepositoryInterface;
use App\Services\Searchable\Searchable;
use Carbon\Carbon;

class ProductTypeRepository implements ProductTypeRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(ProductType $model, Searchable $searchableService)
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
        $productType = ProductType::where('code', $data['code'])->first();

        if ($action === GlobalEnum::ACTION_SYNC_INSERT || $action === GlobalEnum::ACTION_SYNC_UPDATE || $action === GlobalEnum::ACTION_SYNC_IMPORT) {
            // Product Type
            $arrayBody = [
                'code' => $data['code'],
                'name' => $data['name'],
                'type' => $data['type'],
            ];

            if ($productType) {
                $productType->update($arrayBody);
            } else {
                $productType = ProductType::create($arrayBody);
            }

            // Detail
            $arrayBody = [
                'headquarter_id' => $headquarter->id,
                'product_type_id' => $productType ? $productType->id : null
            ];

            $headquarterProductType = HeadquarterProductType::where('headquarter_id', $headquarter->id)
                ->where('product_type_id', $productType->id)->first();

            if ($headquarterProductType) {
                $headquarterProductType->update($arrayBody);
            } else {
                HeadquarterProductType::create($arrayBody);
            }
        }

        return null;
    }

    public function allByHeadquarter($headquarterId, $byCombo = false)
    {
        return ProductType::select(['product_types.id', 'product_types.name'])
            ->join('products', 'product_types.id', 'products.product_type_id')
            ->join('headquarter_product', 'headquarter_product.product_id', 'products.id')
            ->where('headquarter_product.headquarter_id', $headquarterId)
            ->where('headquarter_product.active', 1)
            ->where('headquarter_product.stock', '>',  0)
            ->whereNotNull('headquarter_product.price')
            ->where('products.is_combo', $byCombo)
            ->where('products.is_available', 1)
            ->groupBy(['product_types.id', 'product_types.name'])
            ->orderBy('product_types.code')
            ->get();
    }

    public function allByHeadquarterPromotion($headquarterId, $movie_time_id = null)
    {
        $today = Carbon::now();
        if ($movie_time_id) {
            $movie_time = MovieTime::find($movie_time_id);
            $today = Carbon::parse($movie_time->date_start);
        }
        $promotion_products = ChocoPromotionProduct::whereHas('choco_promotion', function ($query) use ($headquarterId, $today) {
            $query->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->when($headquarterId, function ($query) use ($headquarterId) {
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
        })->whereHas('product', function ($query) use ($headquarterId) {
            $query->whereHas('headquarters', function ($query) use ($headquarterId) {
                $query->where('active', 1)->where('headquarter_id', $headquarterId);
            })->where('is_available', 1);
        })->with(['product.type'])
            ->get();

      return $promotion_products->unique('product.type.id')->sortBy('product.type.code')->transform(function ($product_promotion) {
          return [
              'id' => $product_promotion->product->type->id,
              'name' => $product_promotion->product->type->name,
          ];
       })->values();
    }

    public function allByType($byCombo = false)
    {
        return ProductType::select(['product_types.id', 'product_types.name'])
            ->join('products', 'product_types.id', 'products.product_type_id')
            ->where('products.is_combo', $byCombo)
            ->where('products.is_available', 1)
            ->groupBy(['product_types.id', 'product_types.name'])
            ->orderBy('product_types.code')
            ->get();
    }
}
