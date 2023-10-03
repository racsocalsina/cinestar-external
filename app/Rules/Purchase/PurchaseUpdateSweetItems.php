<?php


namespace App\Rules\Purchase;


use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Purchases\Purchase;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PurchaseUpdateSweetItems implements Rule
{
    private $errorMessage;
    private $purchaseId;
    private $headquarterId;

    public function __construct($purchaseId, $headquarterId)
    {
        $this->purchaseId = $purchaseId;
        $this->headquarterId = $headquarterId;
    }

    public function passes($attribute, $value)
    {
        $purchase = Purchase::find($this->purchaseId);
        $hasFunction = $purchase->movie_time_id ? true : false;
        if($hasFunction)
        {
            // allow send sweets array as empty (skip sweets)
           return true;
        }

        $dataToValidate = [
            'sweets' => $value
        ];

        $validator = Validator::make($dataToValidate, [
            'sweets'              => 'required|array|min:1',
            'sweets.*.id'         => 'required|int',
            'sweets.*.type'       => 'required|in:product,combo',
            'sweets.*.quantity'   => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            $this->errorMessage = $validator->messages()->first();
            return false;
        }

        foreach ($value as $sweet) {

            $entityName = 'producto';
            $data = HeadquarterProduct::with(['product'])
                ->where('headquarter_id', $this->headquarterId)
                ->where('product_id', $sweet['id'])
                ->where('active', 1)
                ->first();

            $entityModel = $data ? $data->product : null;

            // check id exists
            if(is_null($data))
            {
                $this->errorMessage = __('app.sweets.not_exist_with_id', ['entity' => $entityName, 'id' => $sweet['id']]);
                return false;
            }

            // check stock
            if($data->stock <= 0 || $data->stock == null){
                    $this->errorMessage = __('app.sweets.no_stock', ['name' => $entityModel->name]);
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

