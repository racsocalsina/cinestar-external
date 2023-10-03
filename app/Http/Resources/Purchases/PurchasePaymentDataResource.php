<?php


namespace App\Http\Resources\Purchases;


use App\Enums\PurchaseStatus;
use App\Enums\SoldItemTypes;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchasePaymentDataResource extends JsonResource
{
    public $action = 'index';
    public $notCompleted;

    public function setAction(string $action = null, $notCompleted = false)
    {
        if ($action) {
            $this->action = $action;
        }

        $this->notCompleted = $notCompleted;

        return $this;
    }

    public function toArray($request)
    {
        $data = [
            'id'              => $this->id,
            'status'          => $this->status,
            'sold_item_types' => $this->sold_item_types,
            'guid'            => $this->guid,
            'sub_total'       => $this->amount,
            'total'           => $this->amount,
            'headquarter'     => [
                'name'    => $this->headquarter ? $this->headquarter->name : null,
                'address' => $this->headquarter ? $this->headquarter->address : null,
            ],
            'payment_data'    => $this->getPaymentData(),
            'download_url'    => $this->getDownloadUrl(),
            'ticket_data'     => $this->getTicketData(),
            'sweet_data'      => $this->getSweetData(),
        ];

        if ($this->action == 'index') {
            if (strpos($this->sold_item_types, SoldItemTypes::TICKET) !== false)
                $data['tab_type'] = $this->movie_time ? ($this->movie_time->start_at < now() ? 'concluded' : 'valid') : null;
            else
                $data['tab_type'] = 'valid';
        } else {
            $data['message'] = $this->getMessage();
        }

        return $data;
    }

    private function getTicketData()
    {
        if (strpos($this->sold_item_types, SoldItemTypes::TICKET) !== false)
            return [
                'voucher_number' => $this->purchase_ticket ? $this->purchase_ticket->remote_movkey : '',
                'movie'          => [
                    'name'    => $this->movie ? $this->movie->name : '',
                    'genre'   => [
                        'name'  => $this->movie ? ($this->movie->gender ? $this->movie->gender->name : '') : '',
                        'short' => $this->movie ? ($this->movie->gender ? $this->movie->gender->short : '') : '',
                    ],
                    'version' => [
                        'name'  => $this->movie_time ? ($this->movie_time->movie_version ? $this->movie_time->movie_version->name : '') : '',
                        'short' => $this->movie_time ? ($this->movie_time->movie_version ? $this->movie_time->movie_version->short : '') : '',
                    ],
                ],
                'seats'          => count($this->tickets) > 0 ? $this->tickets->unique('seat_name')->pluck('seat_name')->implode(', ') : '',
                'room'           => $this->movie_time ? ($this->movie_time->room ? $this->movie_time->room->room_number : '') : '',
                'ticket_numbers' => $this->number_tickets,
                'ticket_types'   => $this->getTicketTypes(),
                'start_at'       => $this->purchase_ticket ? ($this->purchase_ticket->function_date ? Helper::getDateTimeFormat($this->purchase_ticket->function_date) : '') : '',
                'start_at_name'  => $this->purchase_ticket ? ($this->purchase_ticket->function_date ? Helper::getFriendlyDateFormat($this->purchase_ticket->function_date) : '') : '',
                'qr_url'         => $this->purchase_ticket ? $this->getQrUrl(SoldItemTypes::TICKET) : '',
                'points'         => $this->purchase_ticket ? $this->purchase_ticket->points : 0
            ];

        return null;
    }

    private function getSweetData()
    {
        if (strpos($this->sold_item_types, 'sweet') !== false)
            return [
                'voucher_number' => $this->purchase_sweet ? $this->purchase_sweet->remote_movkey : '',
                'date'           => $this->purchase_sweet ? ($this->purchase_sweet->pickup_date ? Helper::getDateFormat($this->purchase_sweet->pickup_date) : '') : "",
                'date_name'      => $this->purchase_sweet ? ($this->purchase_sweet->pickup_date ? Helper::getFriendlyDateFormat($this->purchase_sweet->pickup_date) : '') : "",
                'items'          => $this->getSweetItems(),
                'qr_url'         => $this->purchase_sweet ? $this->getQrUrl(SoldItemTypes::SWEET) : '',
                'points'         => $this->purchase_sweet ? $this->purchase_sweet->points : 0
            ];

        return null;
    }

    private function getQrUrl($type)
    {
        $guid = isset($this->guid) ? $this->guid : 'no-guid';

        if ($type == SoldItemTypes::TICKET)
            return route('render.purchase-voucher-ticket.qr', $guid);
        else
            return route('render.purchase-voucher-sweet.qr', $guid);
    }

    private function getSweetItems()
    {
        try {
            $uniqueSweets = $this->sweets_sold->unique('code');
            $sweets = $this->sweets_sold;

            return $uniqueSweets->values()->map(function ($item) use ($sweets) {
                $entity = $item->product;
                $quantity = $sweets->where('sweet_id', $item->sweet_id)->count();
                return [
                    'sweet_type' => $item->sweet_type,
                    'name'       => $item->name,
                    'type_name'  => $item->type_name,
                    'image'      => $entity->image_path ? $entity->image_path : asset('assets/img/no-product.png'),
                    'quantity'   => $quantity,
                    'price'      => $item->price,
                ];
            });
        } catch (\Exception $exception) {
            return [];
        }
    }

    private function getTicketTypes()
    {
        try {
            $uniqueMovieTimeTariff = $this->tickets->unique('movie_time_tariff_id');
            $tickets = $this->tickets;
            $purchaseItems = $this->purchase_items;

            return $uniqueMovieTimeTariff->values()->map(function ($item) use ($tickets, $purchaseItems) {
                $quantity = $tickets->where('movie_time_tariff_id', $item->movie_time_tariff_id)->count();
                $purchaseItemsIds = $tickets->where('movie_time_tariff_id', $item->movie_time_tariff_id)->pluck('purchase_item_id');
                $price = $purchaseItems->whereIn('id', $purchaseItemsIds)->first()->paid_amount;

                return [
                    'quantity' => $quantity,
                    'name'     => $item->movie_time_tariff->movie_tariff->name,
                    'price'    => $price
                ];
            });
        } catch (\Exception $exception) {
            return [];
        }
    }

    private function getMessage()
    {
        if ($this->status == PurchaseStatus::ERROR)
            return __('app.purchases.fe_error');

        return null;
    }

    private function getPaymentData()
    {
        if($this->notCompleted)
        {
            return [
                'full_name' => 'Usuario con compra: ' . $this->id,
                'datetime'  => $this->created_at->format('Y-m-d H:i:s'),
                'type'      => 'Payu',
                'card'      => '****',
                'currency'  => 'PEN',
                'total'     => $this->amount,
            ];
        }

        try {
            $paymentGatewayResponse = json_decode($this->payment_gateway_info->payment_gateway_transaction->response);
            $paymentGatewayInfoExtraData = json_decode($this->payment_gateway_info->extra_data);

            if (!isset($paymentGatewayResponse->transactionResponse))
                return null;

            if (!isset($paymentGatewayResponse->transactionResponse->state))
                return null;

            if ($paymentGatewayResponse->transactionResponse->state == "APPROVED")
                return [
                    'full_name' => $this->payment_gateway_info->full_name,
                    'datetime'  => $this->created_at->format('Y-m-d H:i:s'),
                    'type'      => $paymentGatewayInfoExtraData->payment_method,
                    'card'      => $paymentGatewayInfoExtraData->credit_card_masked_number,
                    'currency'  => $paymentGatewayInfoExtraData->currency,
                    'total'     => $this->amount,
                ];

            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function getDownloadUrl()
    {
        $guid = isset($this->guid) ? $this->guid : 'no-guid';
        return route('render.purchase-voucher-ticket.pdf', $guid);
    }
}
