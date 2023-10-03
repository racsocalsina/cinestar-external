<?php


namespace App\Dtos\Purchases;


use App\Models\Purchases\Purchase;
use App\Traits\ObjectToArray;

class PurchaseSweetQrDto
{
    use ObjectToArray;

    private $sold_item_type;
    private $id;

    public static function makeByPurchase(Purchase $purchase): PurchaseSweetQrDto
    {
        $purchase->loadMissing([
            'purchase_sweet'
        ]);

        return new self(
            $purchase->purchase_sweet->remote_movkey
        );
    }

    private function __construct($id)
    {
        $this->sold_item_type = 'sweet';
        $this->id = $id;
    }
}
