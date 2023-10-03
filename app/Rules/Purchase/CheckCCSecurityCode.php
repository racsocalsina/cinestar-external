<?php


namespace App\Rules\Purchase;


use App\Models\Purchases\Purchase;
use Illuminate\Contracts\Validation\Rule;

class CheckCCSecurityCode implements Rule
{
    private $errorMessage;
    private $purchaseId;

    public function __construct($purchaseId)
    {
        $this->purchaseId = $purchaseId;
    }

    public function passes($attribute, $value)
    {
        $purchase = Purchase::find($this->purchaseId);

        if($purchase->amount == 0)
          return true;

        if(trim($value) === "")
        {
            $this->errorMessage = ':attribute es requerido';
            return false;
        }

        if(strlen($value) < 3 || strlen($value) > 4)
        {
            $this->errorMessage = ':attribute no cumple con el formato de 3 o 4 carácteres';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
