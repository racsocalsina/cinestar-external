<?php


namespace App\Models\ChocoPromotions\Repositories;


use App\Enums\GlobalEnum;
use App\Enums\MovieChainEnum;
use App\Enums\TradeName;
use App\Helpers\FileHelper;
use App\Helpers\Helper;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use App\Models\ChocoPromotions\ChocoPromotion;
use App\Models\ChocoPromotions\Repositories\Interfaces\ChocoPromotionRepositoryInterface;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Products\Product;
use App\Models\TypePaymentMethods\TypePaymentMethod;
use App\SearchableRules\ChocoPromotionSearchableRule;
use App\Services\Searchable\Searchable;
use Carbon\Carbon;

class ChocoPromotionRepository implements ChocoPromotionRepositoryInterface
{
    private $searchableService;

    public function __construct(Searchable $searchableService)
    {
        $this->searchableService = $searchableService;
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $promotion = ChocoPromotion::where('code', $data['code'])->first();
        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            if($promotion)
                $promotion->delete();

            return;
        }
        //$headquarter = Headquarter::where('point_sale', $data['point_sale'])->first();
        $type_method = $data['payment_method'] ? TypePaymentMethod::where('remote_code', $data['payment_method']['remote_code'])->first() : null;
        if (!$type_method && !is_null($data['payment_method'])) {
            $type_method = TypePaymentMethod::create([
                'remote_code' => $data['payment_method']['remote_code'],
                'name' => $data['payment_method']['name'],
                'type_currency' => $data['payment_method']['type_currency'],
                'payment_type' => $data['payment_method']['payment_type']
            ]);
        }
        if (isset($promotion->id)) {
            ChocoPromotion::where('code', $data['code'])->update([
                'name' => $data['name'],
                'is_presale' => $data['is_presale'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'discount_rate' => $data['discount_rate'],
                'membership_card_required' => $data['membership_card_required'],
                'type_payment_method_id' => $type_method ? $type_method->id : null,
                'headquarter_id' => $syncHeadquarter ? $syncHeadquarter->id : null,
                'applies_to_all' => intval($data['code']) <= 99,
                'movie_chain' => $data['movie_chain'],
                'is_block_sunday' => $data['is_block_sunday'],
                'is_block_monday' => $data['is_block_monday'],
                'is_block_tuesday' => $data['is_block_tuesday'],
                'is_block_wednesday' => $data['is_block_wednesday'],
                'is_block_thursday' => $data['is_block_thursday'],
                'is_block_friday' => $data['is_block_friday'],
                'is_block_saturday' => $data['is_block_saturday'],
                'promotion_type' => $data['promotion_type'],
            ]);
        } else {
            $promotion = ChocoPromotion::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'is_presale' => $data['is_presale'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'discount_rate' => $data['discount_rate'],
                'membership_card_required' => $data['membership_card_required'],
                'type_payment_method_id' => $type_method ? $type_method->id : null,
                'headquarter_id' => $syncHeadquarter ? $syncHeadquarter->id : null,
                'applies_to_all' => intval($data['code']) <= 99,
                'movie_chain' => $data['movie_chain'],
                'is_block_sunday' => $data['is_block_sunday'],
                'is_block_monday' => $data['is_block_monday'],
                'is_block_tuesday' => $data['is_block_tuesday'],
                'is_block_wednesday' => $data['is_block_wednesday'],
                'is_block_thursday' => $data['is_block_thursday'],
                'is_block_friday' => $data['is_block_friday'],
                'is_block_saturday' => $data['is_block_saturday'],
                'promotion_type' => $data['promotion_type'],
            ]);
        }

        foreach ($data['promotion_products'] as $promotion_product) {
            $product = Product::where('code', $promotion_product['product_code'])->first();
            if ($product) {
                $choco_promotion_product = ChocoPromotionProduct::
                when($product, function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                    ->where('promotion_id', $promotion->id)->first();
                if ($choco_promotion_product) {
                    $choco_promotion_product->price = $promotion_product['price'];
                    $choco_promotion_product->discount_rate = $promotion_product['discount_rate'];
                    $choco_promotion_product->save();
                } else {
                    ChocoPromotionProduct::create([
                        'price' => $promotion_product['price'],
                        'discount_rate' => $promotion_product['discount_rate'],
                        'product_id' => $product->id,
                        'promotion_id' => $promotion->id
                    ]);
                }
            }
        }
    }

    public function syncProducts($body) {

        $action = $body['action'];
        $data = $body['data'];

        $promotion = ChocoPromotion::where('code', $data['promotion_code'])->first();

        if(!isset($promotion->id))
            return;

        $product = Product::where('code', $data['product_code'])->first();

        if(!isset($product->id))
            return;

        $chocoPromotionProduct = ChocoPromotionProduct::where('product_id', $product->id)
            ->where('promotion_id', $promotion->id)->first();

        if(!isset($chocoPromotionProduct->id))
            return;

        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            $chocoPromotionProduct->delete();
            return;
        }

        // update or create
        if ($chocoPromotionProduct) {
            $chocoPromotionProduct->price = $data['price'];
            $chocoPromotionProduct->discount_rate = $data['discount_rate'];
            $chocoPromotionProduct->save();
        } else {
            ChocoPromotionProduct::create([
                'price' => $data['price'],
                'discount_rate' => $data['discount_rate'],
                'product_id' => $product->id,
                'promotion_id' => $promotion->id
            ]);
        }

    }

    public function listAll($request)
    {
        $headquarter_id = $request->get('headquarter_id');
        $today = Carbon::now()->formatLocalized('%A');
        if ($request->has('movie_time_id')){
            $movie_time = MovieTime::find($request->movie_time_id);
            $today = Carbon::parse($movie_time->date_start)->formatLocalized('%A');
        }

        $now = Carbon::now()->format('Y-m-d');
        return ChocoPromotion::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->when($headquarter_id, function ($query) use ($headquarter_id) {
                $query->where(function ($q) use ($headquarter_id) {
                    $q->whereNull('headquarter_id')->orWhere('headquarter_id', $headquarter_id);
                });
            })->where(function ($query) use ($today) {
                if ($today == 'Sunday') {
                    $query->where('is_block_sunday', 0);
                } else if ($today == 'Monday') {
                    $query->where('is_block_monday', 0);
                } else if ($today == 'Tuesday') {
                    $query->where('is_block_tuesday', 0);
                } else if ($today == 'Wednesday') {
                    $query->where('is_block_wednesday', 0);
                } else if ($today == 'Thuerday') {
                    $query->where('is_block_thursday', 0);
                } else if ($today == 'Friday') {
                    $query->where('is_block_friday', 0);
                } else if ($today == 'Saturday') {
                    $query->where('is_block_saturday', 0);
                }
            })
            ->get();
    }

    public function searchBO($request) {
        $query = ChocoPromotion::with(['products.product', 'headquarter', 'payment_method_type']);
        $this->searchableService->applyArray($query, new ChocoPromotionSearchableRule(), $request);
        return $query->orderBy('end_date', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(Helper::perPage($request));
    }

    public function allForApi()
    {
        $tradeName = Helper::getTradeNameHeader();
        $notMovieChain = $tradeName == TradeName::CINESTAR ? MovieChainEnum::MOVIETIME : MovieChainEnum::CINESTAR;

        return ChocoPromotion::whereDate('end_date', '>=', now()->toDateString())
            ->whereRaw('IFNULL(movie_chain, 0) <> '. $notMovieChain)
            ->orderBy('name')
            ->get();
    }

    public function update($data, $request)
    {
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::CHOCO_PROMOTION_FOLDER, $request->file('image'));
            $data->image = $file_name;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }

        $data->save();
        return $data;
    }
}
