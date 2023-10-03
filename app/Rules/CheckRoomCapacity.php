<?php

namespace App\Rules;

use App\Enums\PromotionTypes;
use App\Enums\TicketStatus;
use App\Models\MovieTimes\MovieTime;
use App\Models\TicketAwards\TicketAward;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\Tickets\Ticket;
use Illuminate\Contracts\Validation\Rule;

class CheckRoomCapacity implements Rule
{
    public $movie_time_id;
    public $tickets;
    private $purchaseId;
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($movie_time_id, $tickets, $purchaseId)
    {
        $this->movie_time_id = $movie_time_id;
        $this->tickets = $tickets;
        $this->purchaseId = $purchaseId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $totalTickets = 0;
        foreach ($this->tickets as $ticket) {
            if ($ticket['type'] === PromotionTypes::PREMIO) {
                $award = TicketAward::find($ticket['promotion_id']);

                if(!$award)
                {
                    $this->errorMessage = "Promocion {$ticket['promotion_id']} no existe";
                    return false;
                }

                $totalTickets += $award->promotion->tickets_number * $ticket['quantity'];
            } else if ($ticket['type'] === PromotionTypes::PROMOCION || $ticket['type'] === PromotionTypes::CODIGO) {
                $promotion = TicketPromotion::find($ticket['promotion_id']);

                if(!$promotion)
                {
                    $this->errorMessage = "Promocion {$ticket['promotion_id']} no existe";
                    return false;
                }

                $totalTickets += $promotion->ticket_qty * $ticket['quantity'];
            } else {
                $totalTickets += $ticket['quantity'];
            }

        }

        if(!$this->checkTotalTicketsByPurchase($totalTickets))
            return false;

        if(!$this->checkRoomIsFull())
            return false;

        return true;
    }

    private function checkRoomIsFull(): bool
    {
        $movie_time = MovieTime::find($this->movie_time_id);

        $totalTicketsByFunction = Ticket::join('purchases', 'tickets.purchase_id', 'purchases.id')
            ->where('tickets.status', TicketStatus::COMPLETED)
            ->where('purchases.id', $this->purchaseId)
            ->where('purchases.movie_time_id', $this->movie_time_id)
            ->count();

        $roomCapacityIsFull = $totalTicketsByFunction > $movie_time->room->capacity;

        if($roomCapacityIsFull)
        {
            $this->errorMessage = 'Función completa, ya no hay butacas disponibles';
            return false;
        }

        return true;
    }

    private function checkTotalTicketsByPurchase($totalTickets): bool
    {
        if($totalTickets >= 10)
        {
            $this->errorMessage = 'Se ha superado el limite de 9 entradas por compra';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->errorMessage;
    }
}
