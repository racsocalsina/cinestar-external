<?php


namespace App\Http\Resources\BackOffice\Purchases;


use App\Enums\SoldItemTypes;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchasePaymentDataQR extends JsonResource
{
    private $soldItemType;
    private $data;

    public function toArray($request)
    {
        $this->soldItemType = $this['sold_item_type'];
        $this->data = $this['data'];

        return [
            'sold_item_type'  => $this->soldItemType,
            'id'              => $this->soldItemType == SoldItemTypes::TICKET ? $this->data->purchase_ticket->remote_movkey : $this->data->purchase_sweet->remote_movkey,
            'movie_name'      => $this->soldItemType == SoldItemTypes::TICKET ? $this->data->movie_time->movie->name : null,
            'movie_image'     => $this->soldItemType == SoldItemTypes::TICKET ? $this->data->movie_time->movie->image_path : null,
            'cinema'          => [
                'name'    => $this->data->headquarter->name,
                'address' => $this->data->headquarter->address,
            ],
            'date'            => $this->getDateFromType('d/m/Y'),
            'time'            => $this->getDateFromType('h:i a'),
            'pickup_date'     => $this->getDateFromType('Y-m-d'),
            'items'           => $this->getItems(),
            'room'            => $this->soldItemType == SoldItemTypes::TICKET ? $this->data->movie_time->room->room_number : null,
            'reserves'        => $this->soldItemType == SoldItemTypes::TICKET ? $this->data->tickets->implode('seat_name', ', ') : null,
            'tickets'         => $this->getTickets(),
            'movie_time_date' => $this->getDateFromType('d/m/Y'),
            'movie_time_hour' => $this->getDateFromType('h:i a'),
            'function_date'   => $this->getDateFromType('Y-m-d H:i:s'),
        ];
    }

    private function getDateFromType($format)
    {
        if ($this->soldItemType == SoldItemTypes::SWEET) {
            return $this->data->purchase_sweet->pickup_date ? $this->data->purchase_sweet->pickup_date->format($format) : null;
        } else if ($this->soldItemType == SoldItemTypes::TICKET) {
            return $this->data->purchase_ticket->function_date ? $this->data->purchase_ticket->function_date->format($format) : null;
        }

        return null;
    }

    private function getItems()
    {
        if ($this->soldItemType == SoldItemTypes::TICKET) {
            return null;
        } else {
            $uniqueSweets = $this->data->sweets_sold->unique('code');
            $sweets = $this->data->sweets_sold;

            return $uniqueSweets->values()->map(function ($item) use ($sweets) {
                $quantity = $sweets->where('sweet_id', $item->sweet_id)->count();
                $fileName = $item->product_by_code ? $item->product_by_code->image : null;
                $image = Helper::getImageSweetPathByType($fileName);

                return [
                    'code'       => $item->code,
                    'sweet_type' => $item->sweet_type,
                    'name'       => $item->name,
                    'type_name'  => $item->type_name,
                    'quantity'   => $quantity,
                    'price'      => $item->price,
                    'image'      => $image,
                ];
            });
        }
    }

    private function getTickets()
    {
        if ($this->soldItemType == SoldItemTypes::TICKET) {
            return $this->data->tickets
                ->groupBy(function ($item) {
                    return $item->movie_time_tariff->movie_tariff_id;
                })->transform(function ($items) {
                    $f = $items->first();
                    $quantity = $items->count();
                    $name = $f->movie_time_tariff->movie_tariff->name;
                    return "{$quantity} $name";
                })->values()->toArray();
        } else {
            return null;
        }
    }

}
