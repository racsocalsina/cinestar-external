<?php

namespace App\Rules;

use App\Enums\PromotionTypes;
use App\Helpers\FunctionHelper;
use App\Models\TicketAwards\TicketAward;
use Illuminate\Contracts\Validation\Rule;

class HasPurchasePoints implements Rule
{
    protected $tickets;
    protected $errorMessage;
    protected $user;

    public function __construct($tickets)
    {
        $this->tickets = collect($tickets);
        $this->user = FunctionHelper::getApiUser();
    }

    public function passes($attribute, $value)
    {
        if(!$this->checkAwardsOnlyForAuthenticatedUsers())
            return false;

        $ticket_awards = $this->tickets->where('type', PromotionTypes::PREMIO);

        if ($ticket_awards->count()) {

            $points = 0;
            foreach ($ticket_awards as $ticket) {
                $award = TicketAward::find($ticket['promotion_id']);
                if(!$award)
                {
                    $this->errorMessage = "Promocion {$ticket['promotion_id']} no existe";
                    return false;
                }

                $points += $award->points * $ticket['quantity'];
            }

            if (!$this->user->customer->user_partner_cod || $this->user->customer->user_partner_cod->ticket_points < $points) {
                $this->errorMessage = __('app.points.insufficient_points');
                return false;
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
