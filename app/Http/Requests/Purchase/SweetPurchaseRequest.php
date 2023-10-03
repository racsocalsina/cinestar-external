<?php


namespace App\Http\Requests\Purchase;


use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Products\Product;
use App\Rules\CheckPromotionChoco;
use App\Rules\Purchase\CheckInternalIsAvailable;
use App\Rules\Purchase\CheckPurchaseBeforeUpdate;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SweetPurchaseRequest extends FormRequest
{
    use ApiResponser;

    private $purchase;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'origin' => 'required|in:app,web',
            'headquarter_id' => [
                'required',
                'int',
                'exists:headquarters,id',
                new CheckInternalIsAvailable(null, null, $this->headquarter_id)
            ],
            'purchase_id' => [
                'nullable',
                'int',
                new CheckPurchaseBeforeUpdate($this->purchase_id),
            ],
            'sweets' => 'required|array|min:1',
            'sweets.*.id' => 'required|int',
            'sweets.*.is_presale' =>'int',       
            'sweets.*.type' => 'required|in:product,combo',
            'sweets.*.quantity' => 'required|numeric|min:1',
            'sweets.*.promotion_id' => ['required', new CheckPromotionChoco($this->sweets, $this->purchase_id, $this->headquarter_id)],
            'sweets.*.type_promotion' => 'required|in:NORMAL,PROMOCION,PREMIO',
            /* 'pickup_date' => [ 'date',     
                function ($attribute, $value, $fail){
                    $attribute = "Fecha";
                    foreach($this->sweets as $item){
                        $product = Product::find($item["id"]);
                        if (!($value >= $product->presale_start && $value <= $product->presale_end)) {
                            $fail($attribute.' escogida no se encuentra dentro del rango establecido.');
                        }
                    }
                }] */
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->checkSweets();
            $this->checkDataWhenPurchaseAlreadyIsCreated();
        }
    }

    protected function checkSweets()
    {
        foreach ($this->sweets as $sweet) {
            $entityName = 'producto';
            $data = HeadquarterProduct::with(['product'])
                ->where('headquarter_id', $this->headquarter_id)
                ->where('product_id', $sweet['id'])
                ->where('active', 1)
                ->first();

            $entityModel = $data ? $data->product : null;

            // check id exists
            if (is_null($data))
                throw new HttpResponseException(
                    $this->errorResponse(['message' => __('app.sweets.not_exist_with_id', ['entity' => $entityName, 'id' => $sweet['id']])], 422)
                );

            // check stock
            if ($data->stock <= 0 || $data->stock == null)
                throw new HttpResponseException(
                    $this->errorResponse(['message' => __('app.sweets.no_stock', ['name' => $entityModel->name])], 422)
            );

            if ($sweet['quantity'] > $data->stock)
                throw new HttpResponseException(
                    $this->errorResponse(['message' => __('app.sweets.no_stock_insufficient', ['name' => $entityModel->name])], 422)
            );
        }

    }

    protected
    function checkDataWhenPurchaseAlreadyIsCreated()
    {
        if (!is_null($this->purchase)) {
            // check headquarter
            if ($this->purchase->headquarter_id != $this->headquarter_id)
                throw new HttpResponseException(
                    $this->errorResponse(['message' => __('app.purchases.data_not_match_already_purchase_created', ['field' => 'Sede'])], 422)
                );

            // check origin
            if ($this->purchase->origin != $this->origin)
                throw new HttpResponseException(
                    $this->errorResponse(['message' => __('app.purchases.data_not_match_already_purchase_created', ['field' => 'Origen'])], 422)
                );
        }

    }
}
