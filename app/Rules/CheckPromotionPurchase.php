<?php

namespace App\Rules;

use App\Enums\PromotionTypes;
use App\Helpers\FunctionHelper;
use App\Models\MovieTimes\MovieTime;
use App\Models\TicketAwards\TicketAward;
use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Contracts\Validation\Rule;

class CheckPromotionPurchase implements Rule
{
    protected $tickets;
    protected $movie_time_id;
    protected $errorMessage;
    protected $user;

    public function __construct($tickets, $movie_time_id)
    {
        $this->tickets = $tickets;
        $this->movie_time_id = $movie_time_id;
        $this->user = FunctionHelper::getApiUser();
    }

    public function passes($attribute, $value)
    {
        if(!$this->checkAwardsOnlyForAuthenticatedUsers())
            return false;

        $movie_time = MovieTime::find($this->movie_time_id);

        foreach ($this->tickets as $ticket) {

            if ($ticket['type'] == PromotionTypes::NORMAL)
                continue;

            if ($ticket['type'] == PromotionTypes::PREMIO) {
                $model = TicketAward::find($ticket['promotion_id']);
                if (!$model) {
                    $this->errorMessage = "Promoción no existe";
                    return false;
                }
                if (!$model->promotion->validByPromotion($movie_time)) {
                    $this->errorMessage = "Promoción no disponible";
                    return false;
                }
            } else {
                $model = TicketPromotion::find($ticket['promotion_id']);
                if (!$model) {
                    $this->errorMessage = "Promoción no existe";
                    return false;
                }
                if (!$model->validByPromotion($movie_time)) {
                    $this->errorMessage = "Promoción no disponible";
                    return false;
                }

                if ($model->isBirthday && $this->user) {
                    $num_tickets = $ticket['quantity'] * $model->ticket_qty;
                    if ($num_tickets > $model->tickets_max) {
                        $this->errorMessage = "El cantidad de la promoción supera la cantidad permitida";
                        return false;
                    }
                    if (!$this->user->customer->today_birthday($movie_time)) {
                        $this->errorMessage = "Promoción ya canjeado";
                        return false;
                    }
                }
            }
        }
        return true;
    }

    private function checkAwardsOnlyForAuthenticatedUsers(): bool
    {
        if(!$this->user)
        {
            $ticketAwards = collect($this->tickets)->filter(function ($item) {
                return $item['type'] == PromotionTypes::PREMIO;
            });

            if($ticketAwards->count() > 0)
            {
                $this->errorMessage = __('app.purchases.awards_not_allowed_for_guest');
                return false;
            }

        }

        return true;
    }

    public function message()
    {
       return $this->errorMessage;
    }
}
