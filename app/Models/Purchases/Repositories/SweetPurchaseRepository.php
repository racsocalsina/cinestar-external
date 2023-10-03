<?php


namespace App\Models\Purchases\Repositories;


use App\Enums\PromotionTypes;
use App\Enums\PurchaseStatus;
use App\Helpers\FunctionHelper;
use App\Http\Requests\Purchase\SweetPurchaseRequest;
use App\Http\Requests\Purchase\UpdateSweetPurchaseRequest;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\PurchaseItems\PurchaseItem;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\SweetPurchaseRepositoryInterface;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\SweetsSold\SweetSold;
use Illuminate\Support\Facades\Log;

class SweetPurchaseRepository implements SweetPurchaseRepositoryInterface
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(SweetPurchaseRequest $request)
    {
        $purchase = null;
        $user = FunctionHelper::getApiUser();
        $totalTicket = 0;

        // Create or update purchase
        if (is_null($request->purchase_id)) {
            // create
            $purchase = Purchase::create([
                'origin' => $request->origin,
                'user_id' => $user ? $user->id : null,
                'headquarter_id' => $request->headquarter_id,
                'status' => PurchaseStatus::PENDING,
                'amount' => 0,
            ]);

        } else {

            // update
            $purchase = Purchase::findOrFail($request->purchase_id);
            $purchaseTicket = PurchaseTicket::where('purchase_id', $request->purchase_id)->first();
            $totalTicket = $purchaseTicket ? $purchaseTicket->total : 0;

            $purchase->update([
                'origin' => $request->origin,
                'user_id' => $user ? $user->id : null,
                'headquarter_id' => $request->headquarter_id,
                'status' => PurchaseStatus::PENDING,
                'amount' => 0,
            ]);

            // reset (delete) details
            PurchaseSweet::where('purchase_id', $purchase->id)->forceDelete();
        }

        // create PurchaseSweet (new detail)
        $purchaseSweet = PurchaseSweet::create([
            'purchase_id' => $purchase->id,
            'total' => 0
        ]);

        // calculate values and update total
        $totalSweets = $this->createItemsAndReturnData($purchase, $request, $purchaseSweet);
        $totalGlobal = floatval($totalSweets) + floatval($totalTicket);
        $purchase->update(['amount' => $totalGlobal]);
        $purchaseSweet->update(['total' => $totalSweets]);

        return [
            'purchase' => $purchase,
            'graph' => $purchase->movie_time ? $purchase->movie_time->planner_graph : null,
            'original_graph' => $purchase->movie_time ? $purchase->movie_time->room->planner_graph : null,
            'business_name' => $purchase->headquarter->business_name
        ];
    }

    public function update($id, UpdateSweetPurchaseRequest $request)
    {
        $purchase = Purchase::where('id', $id)->first();

        // reset (delete) details
        PurchaseSweet::where('purchase_id', $purchase->id)->forceDelete();

        $sweetsTotal = 0;

        if($request->sweets && count($request->sweets) > 0){
            // create PurchaseSweet (new detail)
            $purchaseSweet = PurchaseSweet::create([
                'purchase_id' => $purchase->id,
                'total' => 0
            ]);
            $sweetsTotal = $this->createItemsAndReturnData($purchase, $request, $purchaseSweet);
            $purchaseSweet->update(['total' => $sweetsTotal]);
        }

        // calculate values and update total
        $globalTotal = $this->getGlobalTotal($purchase->id, $sweetsTotal);
        $purchase->update(['amount' => $globalTotal]);

        return [
            'purchase' => $purchase,
            'graph' => $purchase->movie_time ? $purchase->movie_time->planner_graph : null,
            'original_graph' => $purchase->movie_time ? $purchase->movie_time->room->planner_graph : null,
            'business_name' => $purchase->headquarter->business_name
        ];
    }

    private function createItemsAndReturnData(Purchase $purchase, $request, $purchaseSweet)
    {
        $total = 0;
        foreach ($request->sweets as $sweet) {
            $entity = HeadquarterProduct::with(['product.type'])
                ->where('headquarter_id', $request->headquarter_id)
                ->where('product_id', $sweet['id'])
                ->first();

            $price = $entity->price;
            $purchase_promotion = null;
            if ($sweet['type_promotion'] == PromotionTypes::PREMIO) {
                $price = 0;
                $choco_award = ChocoAward::find($sweet['promotion_id']);
                $purchase_promotion = $choco_award->purchase_promotion()->create([
                    'purchase_id' => $purchase->id,
                    'qty' => $sweet['quantity']
                ]);
            }else if ($sweet['type_promotion'] == PromotionTypes::PROMOCION){
                $promotion_product = ChocoPromotionProduct::find($sweet['promotion_id']);
                $price = $this->productRepository->applicatePromotionPrice($purchase->headquarter_id, $promotion_product->product, $promotion_product);
                $purchase_promotion =$promotion_product->purchase_promotion()->create([
                    'purchase_id' => $purchase->id,
                    'qty' => $sweet['quantity']
                ]);
            }

            $total += $price * $sweet['quantity'];
            $index = $sweet['quantity'];

            while ($index > 0) {

                // create details
                $item = PurchaseItem::create([
                    'original_amount' => $price,
                    'paid_amount' => $price,
                    'purchase_id' => $purchase->id,
                    'purchase_sweet_id' => $purchaseSweet->id
                ]);

                SweetSold::create([
                    'purchase_id' => $purchase->id,
                    'purchase_item_id' => $item->id,
                    'headquarter_product_id' => $entity->id,
                    'sweet_type' => $sweet['type'],
                    'sweet_id' => $sweet['id'],
                    'code' => $entity->product->code,
                    'name' => $entity->product->name,
                    'type_id' => $entity->product->type->id,
                    'type_name' => $entity->product->type->name,
                    'price' => $price,
                    'purchase_promotion_id' => $purchase_promotion ? $purchase_promotion->id : null
                ]);

                $index--;
            }

        }
        return $total;
    }

    private function getGlobalTotal($purchaseId, $sweetTotal)
    {
        $purchaseTicket = PurchaseTicket::where('purchase_id', $purchaseId)->first();
        $ticketTotal = $purchaseTicket ? $purchaseTicket->total : 0;

        return floatval($ticketTotal) + floatval($sweetTotal);
    }

}
