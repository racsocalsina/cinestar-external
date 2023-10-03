<?php


namespace App\Models\ChocoAwards\Repositories;


use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\ChocoAwards\Repositories\Interfaces\ChocoAwardRepositoryInterface;
use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Headquarters\Headquarter;
use App\Models\Products\Product;
use App\Models\Purchases\Purchase;
use App\Models\TicketAwards\TicketAward;
use App\SearchableRules\ChocoAwardSearchableRule;
use App\Services\Searchable\Searchable;

class ChocoAwardRepository implements ChocoAwardRepositoryInterface
{
    private $searchableService;
    private $repository;

    public function __construct(Searchable $searchableService,
                                ChocoAward $repository)
    {
        $this->searchableService = $searchableService;
        $this->repository = $repository;
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $award = ChocoAward::where('code', $data['code'])->first();
        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            $award->delete();
            return;
        }

        $product = Product::where('code', $data['product_code'])->first();

        if (isset($award->id)) {
            ChocoAward::where('code', $data['code'])->update([
                'name' => $data['name'],
                'points' => $data['points'],
                'product_id' => isset($product) ? $product->id : null,
                'restrictions' => $data['restrictions'],
                'unit' => $data['unit'],
            ]);
        } else {
            ChocoAward::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'points' => $data['points'],
                'product_id' => isset($product) ? $product->id : null,
                'restrictions' => $data['restrictions'],
                'unit' => $data['unit'],
            ]);
        }
    }

    public function searchBO($request)
    {
        $query = ChocoAward::with(['product']);
        $this->searchableService->applyArray($query, new ChocoAwardSearchableRule(), $request);
        return $query->paginate(Helper::perPage($request));
    }

    public function getData()
    {
        return $this->repository->with(['product'])
            ->whereNotNull('product_id')
            ->get();
    }

    public function valid($request)
    {
        $user = FunctionHelper::getApiUser();
        $headquarter = Headquarter::find($request->headquarter_id);
        $choco_award = ChocoAward::find($request->choco_award_id);
        $purchase = null;
        $points_use = $choco_award->points * $request->quantity;

        if ($request->has('purchase_id')) {
            $purchase = Purchase::find($request->purchase_id);
            if ($purchase->promotions->where('replace_type', TicketAward::class)->count()) {
                $points_use += $purchase->promotions->where('replace_type', TicketAward::class)->sum('replacement.points');

            }
//            else if ($purchase->promotions->where('replace_type', ChocoAward::class)->count()){
//                $points_use += $purchase->promotions->where('replace_type', ChocoAward::class)->sum('replacement.points');
//            }
        }
        $sweets = collect($request->sweets);
        $choco_sweets = $sweets->whereNotNull('choco_award_id');
        if ($choco_sweets->count()) {
            foreach ($choco_sweets as $sweets_award) {
                $model = ChocoAward::find($sweets_award['choco_award_id']);
                $points_use += $model->points;
            }
        }

        if(!$user)
            throw new \Exception(__('app.purchases.awards_not_allowed_for_guest'));

        if ($user->customer->user_partner_cod->choco_points < $points_use) {
            throw new \Exception(__('app.points.insufficient_points'));
        }
        $headquarter_product = HeadquarterProduct::where('headquarter_id', $headquarter->id)
            ->where('product_id', $choco_award->product_id)
            ->where('active', 1)
            ->first();

        if (!$headquarter_product) {
            throw new \Exception('Producto no disponible para esta sede');
        }
//        if ($product->stock){
//            throw new \Exception('Producto sin Stock');
//        }

        $quantity = $request->quantity;
        $points = $choco_award->points * $quantity;
        $exists = false;

        if ($choco_sweets->count()) {
            foreach ($choco_sweets as $i => $item) {
                $model = ChocoAward::find($item['choco_award_id']);
                if ($item['choco_award_id'] == $choco_award->id) {
                    $exists = true;
                    $new_quantity = $quantity + $item['quantity'];
                    $new_points = $points + ($model->points * $item['quantity']);
                } else {
                    $new_quantity = $item['quantity'];
                    $new_points = $model->points * $new_quantity;
                }
                $sweets[$i] = [
                    'id' => $model->product->id,
                    'quantity' => $new_quantity,
                    'type' => $model->product->is_combo ? 'combo' : 'product',
                    'choco_award_id' => $model->id,
                    'ticket_award_name' => $model->name,
                    'image' => $model->product->image_path ? $model->product->image_path : asset('assets/img/no-product.png'),
                    'points' => $new_points,
                    'amount' => 0
                ];

            }
        }
        if (!$exists) {
            $sweets[] = [
                'id' => $headquarter_product->product->id,
                'quantity' => $quantity,
                'type' => $headquarter_product->product->is_combo ? 'combo' : 'product',
                'choco_award_id' => $choco_award->id,
                'ticket_award_name' => $choco_award->name,
                'image' => $headquarter_product->product->image_path ? $headquarter_product->product->image_path : asset('assets/img/no-product.png'),
                'points' => $points,
                'amount' => 0
            ];
        }
        return ['sweets' => $sweets];
    }

    public function allForApi()
    {
        return ChocoAward::orderBy('name')->get();
    }

    public function update($data, $request)
    {
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::CHOCO_AWARDS_FOLDER, $request->file('image'));
            $data->image = $file_name;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }

        $data->save();
        return $data;
    }

}
