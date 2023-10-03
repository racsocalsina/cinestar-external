<?php

namespace App\Rules;

use App\Enums\PromotionTypes;
use App\Helpers\FunctionHelper;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\ChocoPromotionProducts\ChocoPromotionProduct;
use Illuminate\Contracts\Validation\Rule;

class CheckPromotionChoco implements Rule
{
    protected $type;
    protected $sweets;
    protected $purchase_id;
    protected $headquarter_id;
    protected $errorMessage;
    protected $user;

    public function __construct($sweets, $purchase_id, $headquarter_id)
    {
        $this->sweets = collect($sweets);
        $this->purchase_id = $purchase_id;
        $this->headquarter_id = $headquarter_id;
        $this->user = FunctionHelper::getApiUser();
    }

    public function passes($attribute, $value)
    {
        if(!$this->checkAwardsOnlyForAuthenticatedUsers())
            return false;

        if(!$this->checkPointsAwardsAndPromotions())
            return false;

        return true;
    }

    private function checkPointsAwardsAndPromotions(): bool
    {
        $sweet_promotions = $this->sweets->filter(function ($item) {
            return $item['type_promotion'] == PromotionTypes::PREMIO || $item['type_promotion'] == PromotionTypes::PROMOCION;
        });

        $points = 0;

        foreach ($sweet_promotions as $sweet) {
            if ($sweet['type_promotion'] == PromotionTypes::PREMIO) {
                $model = ChocoAward::find($sweet['promotion_id']);
                if (!$model) {
                    $this->errorMessage = "Promoción no existe";
                    return false;
                }
                $points += $model->points * $sweet['quantity'];
            }

            if ($sweet['type_promotion'] == PromotionTypes::PROMOCION) {
                $model = ChocoPromotionProduct::find($sweet['promotion_id']);
                if (!$model) {
                    $this->errorMessage = "Promoción no existe";
                    return false;
                }
                if (!$model->choco_promotion->validByPromotion($this->purchase_id,   $this->headquarter_id)){
                    $this->errorMessage = "Promoción no disponible";
                    return false;
                }
            }
        }

        if($this->user) {
            if (!$this->user->customer->user_partner_cod || $this->user->customer->user_partner_cod->choco_points < $points) {
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
            $sweetAwards = $this->sweets->filter(function ($item) {
                return $item['type_promotion'] == PromotionTypes::PREMIO;
            });

            if($sweetAwards->count() > 0)
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
