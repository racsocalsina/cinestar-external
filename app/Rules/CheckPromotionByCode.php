<?php

namespace App\Rules;

use App\Enums\PromotionTypes;
use App\Models\PromotionCorporative\PromotionCorporative;
use Illuminate\Contracts\Validation\Rule;

class CheckPromotionByCode implements Rule
{
    protected $tickets;
    protected $errorMessage;

    public function __construct($tickets)
    {
        $this->tickets = collect($tickets);
    }

    public function passes($attribute, $value)
    {
       $ticket_codes = $this->tickets->where('type', PromotionTypes::CODIGO);
       if ($ticket_codes->count()){
           foreach ($ticket_codes as $ticket){
               $codes = collect($ticket['codes']);
               foreach ($codes as $code){
                   $model = PromotionCorporative::where('codigo', $code)->first();
                   if (!$model){
                       $this->errorMessage = "Código de promoción no existe";
                       return false;
                   }
                   if ($model->estado){
                       $this->errorMessage = "Código de promoción no existe";
                       return false;
                   }
               }
               if ($codes->count() > $codes->unique()->count()){
                   $this->errorMessage = "Cógidos de promoción repetidos";
                    return false;
               }
           }
       }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
