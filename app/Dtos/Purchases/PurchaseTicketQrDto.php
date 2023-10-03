<?php


namespace App\Dtos\Purchases;


use App\Enums\SoldItemTypes;
use App\Models\Purchases\Purchase;
use App\Traits\ObjectToArray;

class PurchaseTicketQrDto
{
    use ObjectToArray;

    private $sold_item_type;
    private $id;

    public static function makeByPurchase(Purchase $purchase): PurchaseTicketQrDto
    {
        $purchase->loadMissing([
            'purchase_ticket'
        ]);

        return new self(
            $purchase->purchase_ticket->remote_movkey
        );
    }

    private function __construct($id)
    {
        $this->sold_item_type = SoldItemTypes::TICKET;
        $this->id = $id;
    }
}
