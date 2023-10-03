<?php

namespace App\Rules\Purchase;

use App\Enums\PurchaseStatus;
use App\Helpers\FunctionHelper;
use App\Models\Purchases\Purchase;
use Illuminate\Contracts\Validation\Rule;

class CheckPurchaseBeforeUpdate implements Rule
{
    protected $errorMessage;
    protected $purchase;
    protected $user;

    public function __construct($purchaseId)
    {
        $this->user = FunctionHelper::getApiUser();

        if(!is_null($purchaseId))
            $this->purchase = Purchase::find($purchaseId);
    }

    public function passes($attribute, $value)
    {
        if(!$this->purchase)
            return true;

        if(!$this->checkPurchase())
            return false;

        if(!$this->checkPurchaseStatus())
            return false;

        if(!$this->checkAuthenticationTypeOfPurchase())
            return false;

        return true;
    }

    private function checkPurchase(): bool
    {
        if(is_null($this->purchase))
        {
            $this->errorMessage = __('app.purchases.not_exist');
            return false;
        }
        return true;
    }

    protected function checkPurchaseStatus(): bool
    {
        if($this->purchase->confirmed)
        {
            $this->errorMessage = __('app.purchases.already_confirmed');
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
            $this->errorMessage = "No se puede actualizar esta compra porque fue creada con un usuario autenticado";
            return false;
        }

        if(!$purchaseIsWithUserAuthenticated && $requestAsUserAuthenticated)
        {
            $this->errorMessage = "No se puede actualizar esta compra porque fue creada con un usuario invitado";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
