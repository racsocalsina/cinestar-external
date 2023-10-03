<?php


namespace App\Http\Resources\BackOffice\Purchases;

use App\Enums\GlobalEnum;
use App\Enums\PurchaseStatus;
use App\Enums\PurchaseStatusTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseTransactionColletionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'sales_types'          => $this->getSalesTypes(),
            'movie_name'           => $this->movie ? $this->movie->name : null,
            'headquarter_name'     => $this->headquarter ? $this->headquarter->name : null,
            'guest'                => $this->user ? false : true,
            'user_code'            => $this->user ? ($this->user->customer ? $this->user->customer->socio_cod : null) : null,
            'user_name'            => $this->user ? ($this->user->customer ? $this->user->customer->full_name : null) : null,
            'total'                => $this->amount,
            'voucher_type'         => $this->voucher_type,
            'status'               => $this->transaction_status,
            'status_name'          => PurchaseStatusTransaction::getStatusName($this->transaction_status),
            'number_tickets'       => $this->number_tickets,
            'origin'               => $this->origin,
            'created_at'           => $this->created_at->format('d/m/y h:i a'),
            'remote_movkeys'       => $this->getRemoteKeys(),
            'sold_item_type'       => $this->sold_item_type,
            'ticket'               => $this->purchase_ticket ? true : false,
            'ticket_remote_movkey' => $this->purchase_ticket ? $this->purchase_ticket->remote_movkey : null,
            'ticket_send_fe'       => $this->purchase_ticket ? $this->purchase_ticket->send_fe : null,
            'ticket_send_internal' => $this->purchase_ticket ? $this->purchase_ticket->send_internal : null,
            'ticket_points'        => $this->purchase_ticket ? $this->purchase_ticket->points : null,
            'ticket_total'         => $this->purchase_ticket ? $this->purchase_ticket->total : null,
            'ticket_function_date' => $this->purchase_ticket ? ($this->purchase_ticket->function_date ? $this->purchase_ticket->function_date->format('d/m/y h:i a') : null) : null,
            'ticket_seats'         => count($this->tickets) > 0 ? $this->tickets->unique('seat_name')->pluck('seat_name')->implode(', ') : null,
            'ticket_room'          => $this->movie_time ? ($this->movie_time->room ? $this->movie_time->room->room_number : null) : null,
            'ticket_types'         => $this->getTicketTypes(),
            'ticket_date_issue'    => $this->purchase_ticket ? ($this->purchase_ticket->purchase_voucher ? $this->purchase_ticket->purchase_voucher->date_issue->format('d/m/y h:i a') : null) : null,

            'sweet'               => $this->purchase_sweet ? true : false,
            'sweet_remote_movkey' => $this->purchase_sweet ? $this->purchase_sweet->remote_movkey : null,
            'sweet_send_fe'       => $this->purchase_sweet ? $this->purchase_sweet->send_fe : null,
            'sweet_send_internal' => $this->purchase_sweet ? $this->purchase_sweet->send_internal : null,
            'sweet_points'        => $this->purchase_sweet ? $this->purchase_sweet->points : null,
            'sweet_total'         => $this->purchase_sweet ? $this->purchase_sweet->total : null,
            'sweet_pickup_date'   => $this->purchase_sweet ? ($this->purchase_sweet->pickup_date ? $this->purchase_sweet->pickup_date->format('d/m/y') : null) : null,
            'sweet_items'         => $this->getSweetItems(),
            'sweet_date_issue'    => $this->purchase_sweet ? ($this->purchase_sweet->purchase_voucher ? $this->purchase_sweet->purchase_voucher->date_issue->format('d/m/y h:i a') : null) : null,

            'send_fe'       => $this->getSendFe(),
            'send_internal' => $this->getSendInternal(),
            'payment_data'  => $this->getPaymentData(),
            'download_url'  => $this->getDownloadUrl(),
        ];
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

    private function getPaymentData()
    {
        if ($this->confirmed) {
            $paymentGatewayInfoExtraData = json_decode($this->payment_gateway_info->extra_data);

            return [
                'full_name' => $this->payment_gateway_info->full_name,
                'datetime'  => $this->created_at->format('Y-m-d H:i:s'),
                'type'      => $paymentGatewayInfoExtraData->payment_method,
                'card'      => $paymentGatewayInfoExtraData->credit_card_masked_number,
                'currency'  => $paymentGatewayInfoExtraData->currency,
                'total'     => $this->amount,
            ];
        }
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

    private function getDownloadUrl()
    {
        $guid = isset($this->guid) ? $this->guid : 'no-guid';
        return route('render.purchase-voucher-ticket.pdf', $guid);
    }

    private function getSalesTypes(): array
    {
        $array = [];

        if ($this->purchase_ticket) {
            array_push($array, ['type' => 'ticket', 'value' => 'Boleteria']);
        }

        if ($this->purchase_sweet) {
            array_push($array, ['type' => 'sweet', 'value' => 'Chocolateria']);
        }
        return $array;
    }

    private function getRemoteKeys(): array
    {
        $array = [];

        if ($this->purchase_ticket) {
            array_push($array, ['type' => 'ticket', 'value' => $this->purchase_ticket->remote_movkey]);
        }

        if ($this->purchase_sweet) {
            array_push($array, ['type' => 'sweet', 'value' => $this->purchase_sweet->remote_movkey]);
        }
        return $array;
    }

    private function getSendFe(): ?string
    {
        $status = null;

        if ($this->purchase_ticket) {
            if ($this->purchase_ticket->send_fe != GlobalEnum::COMPLETED_STATUS)
                return null;
        }

        if ($this->purchase_sweet) {
            if ($this->purchase_sweet->send_fe != GlobalEnum::COMPLETED_STATUS)
                return null;
        }

        if ($this->status == PurchaseStatus::COMPLETED) {
            $status = GlobalEnum::COMPLETED_STATUS;
        }

        return $status;
    }

    private function getSendInternal(): ?string
    {
        $status = null;

        if ($this->purchase_ticket) {
            if ($this->purchase_ticket->send_internal != GlobalEnum::COMPLETED_STATUS)
                return null;
        }

        if ($this->purchase_sweet) {
            if ($this->purchase_sweet->send_internal != GlobalEnum::COMPLETED_STATUS)
                return null;
        }

        if ($this->status == PurchaseStatus::COMPLETED) {
            $status = GlobalEnum::COMPLETED_STATUS;
        }

        return $status;
    }
}