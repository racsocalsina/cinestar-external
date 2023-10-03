<?php

namespace App\Rules;

use App\Enums\InternalValidationResponseType;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\PromotionCorporative\PromotionCorporative;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\Purchases\Purchase;
use App\Models\TicketAwards\TicketAward;
use App\Models\Tickets\Ticket;
use Illuminate\Contracts\Validation\Rule;

class CheckPurchaseBeforePay implements Rule
{
    protected $tokenCard;
    protected $ccNumber;
    protected $errorMessage;
    protected $purchase;
    protected $user;

    public function __construct($tokenCard, $ccNumber)
    {
        $this->tokenCard = $tokenCard;
        $this->ccNumber = $ccNumber;
    }

    public function passes($attribute, $value)
    {
        $this->purchase = Purchase::with(['headquarter', 'movie_time'])
            ->where('id', $value)
            ->first();
        $this->user = FunctionHelper::getApiUser();

        if(!$this->checkAuthenticationTypeOfPurchase())
            return false;

        if(!$this->checkSeatsAvailability())
           return false;

        if ($this->purchase->promotions->count()) {

            if(!$this->validationByPoints())
                return false;

            if(!$this->validationByCodePromotion())
                return false;

            if(!$this->validationByBirthdayPromotion())
                return false;

            if(!$this->validationByPaymentMethod())
                return false;
        }
        return true;
    }

    private function checkAuthenticationTypeOfPurchase(): bool
    {
        $purchaseIsWithUserAuthenticated = $this->purchase->user_id != null;
        $requestAsUserAuthenticated = $this->user != null;

        if($purchaseIsWithUserAuthenticated && !$requestAsUserAuthenticated)
        {
            $this->errorMessage = "No se puede pagar esta compra porque fue creada con un usuario autenticado";
            return false;
        }

        if(!$purchaseIsWithUserAuthenticated && $requestAsUserAuthenticated)
        {
            $this->errorMessage = "No se puede pagar esta compra porque fue creada con un usuario invitado";
            return false;
        }

        return true;
    }

    private function validationByPoints(): bool
    {
        if(!$this->user)
            return true;

        $awards = $this->purchase->promotions->filter(function ($promotion) {
            return $promotion->replace_type == TicketAward::class || $promotion->replace_type == ChocoAward::class;
        });
        if ($awards->count()) {
            $points_tickets = 0;
            $points_chocos = 0;
            foreach ($awards as $award) {
                if ($award->replacement instanceof TicketAward) {
                    $ticketAward = TicketAward::find($award->replace_id);
                    $points_tickets += $ticketAward->points * $award->qty;
                } else {
                    $chocoAward = ChocoAward::find($award->replace_id);
                    $points_chocos += $chocoAward->points * $award->qty;
                }
            }
            if ($this->user->customer->user_partner_cod->ticket_points < $points_tickets) {
                $this->errorMessage = __('app.points.insufficient_points');
                return false;
            }
            if ($this->user->customer->user_partner_cod->choco_points < $points_chocos) {
                $this->errorMessage = __('app.points.insufficient_points');
                return false;
            }
        }

        return true;
    }

    private function validationByCodePromotion(): bool
    {
        $promotion_codes = $this->purchase->promotions->whereNotNull('codes');
        if ($promotion_codes->count()) {
            foreach ($promotion_codes as $item) {
                foreach ($item->codes as $code) {
                    $model = PromotionCorporative::where('codigo', $code)->first();
                    if (!$model || $model->estado) {
                        $this->errorMessage = "Código no existe";
                        return false;
                    }

                    // check if code is being used
                    $purchasePromotions = PurchasePromotion::where('codes', 'like', '%"'.trim($code).'"%')->get();

                    if(count($purchasePromotions) > 0)
                    {
                        if($purchasePromotions->first()->purchase_id != $this->purchase->id)
                        {
                            $this->errorMessage = "El código promocional " . $code . " ya esta siendo usado";
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    private function validationByBirthdayPromotion(): bool
    {
        if(!$this->user)
            return true;

        $promotion_birthday = $this->purchase->promotions->where('replacement.isBirthday', true)->first();
        if ($promotion_birthday) {
            if (!$this->user->customer->today_birthday($this->purchase->movie_time)) {
                $this->errorMessage = "Promoción ya canjeada";
                return false;
            }
        }

        return true;
    }

    private function validationByPaymentMethod(): bool
    {
        $promotion_methods = $this->purchase->promotions->whereNotNull("replacement.type_payment_method_id");
        if ($promotion_methods->count()){

            $bin = strlen($this->ccNumber) >= 6 ? substr($this->ccNumber, 0, 6) : null;

            if($this->user)
            {
                $card = $this->user->cards->where('token', $this->tokenCard)->first();
                if(!$card)
                {
                    $this->errorMessage = "Token de la tarjeta no existe";
                    return false;
                }

                $bin = $card->bin;
            }

            if (!$bin){
                $this->errorMessage = "Tarjeta no válida";
                return false;
            }

            foreach ($promotion_methods as $promotion_method){
                if (!$promotion_method->replacement->validPaymentMethod($bin)){
                    $this->errorMessage = "Tarjeta no válida, la promoción debe ser pagada con {$promotion_method->replacement->payment_method_type->name}";
                    return false;
                }
            }
        }

        return true;
    }

    private function checkSeatsAvailability(): bool
    {
        $tickets = Ticket::where('purchase_id', $this->purchase->id)->get();

        if($tickets->count() == 0)
            return true;

        if($tickets->whereNull('seat_name')->count() > 0)
        {
            $this->errorMessage = "Hay butacas no seleccionadas";
            return false;
        }

        $seats = Ticket::where('purchase_id', $this->purchase->id)
            ->whereNotNull('seat_name')
            ->pluck('seat_name')
            ->toArray();

        if(count($seats) > 0)
        {
            $response = Helper::checkSeatsAvailabilityByPurchase($this->purchase, $seats);

            $type = $response['data']['type'];
            $seatsResponse = $response['data']['seats'];

            if($type == InternalValidationResponseType::ERROR)
            {
                $this->errorMessage = "Se ha producido un error al validar las butacas en internal";
                return false;

            } else if ($type == InternalValidationResponseType::SEATS_NOT_MATCH) {
                $seatsCommas = implode(',', $seatsResponse);

                if(count($seatsResponse) == 1)
                    $this->errorMessage = "La butaca {$seatsCommas} no esta guardada en la sede";
                else
                    $this->errorMessage = "Las butacas {$seatsCommas} no estan guardadas en la sede";

                return false;

            } else if ($type == InternalValidationResponseType::SEATS_ALREADY_OCCUPIED) {
                $seatsCommas = implode(',', $seatsResponse);

                if(count($seatsResponse) == 1)
                    $this->errorMessage = "La butaca {$seatsCommas} ya esta ocupada";
                else
                    $this->errorMessage = "Las butacas {$seatsCommas} ya estan ocupadas";

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
