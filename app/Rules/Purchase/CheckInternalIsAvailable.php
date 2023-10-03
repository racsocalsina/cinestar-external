<?php

namespace App\Rules\Purchase;

use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Purchases\Purchase;
use Illuminate\Contracts\Validation\Rule;

class CheckInternalIsAvailable implements Rule
{
    protected $errorMessage;
    protected $purchaseId;
    protected $movieTimeId;
    protected $headquarterId;
    protected $user;

    public function __construct($purchaseId, $movieTimeId = null, $headquarterId = null)
    {
        $this->purchaseId = $purchaseId;
        $this->movieTimeId = $movieTimeId;
        $this->headquarterId = $headquarterId;
    }

    public function passes($attribute, $value)
    {
        try {

            if($this->purchaseId != null)
            {
                $purchase = Purchase::with('headquarter')->where('id', $this->purchaseId)->first();
                $headquarter = $purchase->headquarter;
            } else if($this->movieTimeId != null) {

                $movieTime = MovieTime::with('headquarter')->where('id', $this->movieTimeId)->first();
                $headquarter = $movieTime->headquarter;

            } else if($this->headquarterId != null) {

                $headquarter = Headquarter::find($this->headquarterId);
            }

            $token = Helper::loginInternal($headquarter);

            if (is_null($token)){
                $this->errorMessage = __('app.internal_app.no_connection');
                return false;
            }

            return true;

        } catch (\Exception $exception) {
            $this->errorMessage = __('app.internal_app.no_connection') . ', ' . $exception->getMessage();
            return false;
        }
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
