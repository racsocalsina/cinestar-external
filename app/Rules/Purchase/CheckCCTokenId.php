<?php


namespace App\Rules\Purchase;


use App\Helpers\FunctionHelper;
use App\Models\Cards\Card;
use App\Models\Purchases\Purchase;
use Illuminate\Contracts\Validation\Rule;

class CheckCCTokenId implements Rule
{
    private $errorMessage;
    private $purchaseId;
    private $user;

    public function __construct($purchaseId)
    {
        $this->purchaseId = $purchaseId;
        $this->user = FunctionHelper::getApiUser();
    }

    public function passes($attribute, $value)
    {
        if(!$this->user)
            return true;

        $purchase = Purchase::find($this->purchaseId);

        if($purchase->amount == 0)
          return true;

        if(trim($value) === "")
        {
            $this->errorMessage = ':attribute es requerido';
            return false;
        }

        $card = Card::where('token', $value)
            ->where('user_id', $this->user->id)
            ->first();

        if(!$card)
        {
            $this->errorMessage = ':attribute no existe';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
