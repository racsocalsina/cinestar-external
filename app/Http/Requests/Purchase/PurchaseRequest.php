<?php


namespace App\Http\Requests\Purchase;


use App\Rules\CheckPromotionByCode;
use App\Rules\CheckPromotionPurchase;
use App\Rules\CheckRoomCapacity;
use App\Rules\HasPurchasePoints;
use App\Rules\Purchase\CheckInternalIsAvailable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PurchaseRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'origin' => 'origen',
            'movie_time_id' => 'función',
            'tickets.*.movie_time_tariff_id' => 'tarifa',
            'tickets.*.quantity' => 'cantidad'
        ];
    }

    public function rules()
    {
        return [
            'origin' => 'required|in:app,web',
            'movie_time_id' => [
                'bail',
                'required',
                'exists:movie_times,id',
                new CheckRoomCapacity(request('movie_time_id'), request('tickets'), 0),
                new CheckInternalIsAvailable(null, request('movie_time_id'))
            ],
            'tickets' => 'required|array|min:1',
            'tickets.*.promotion_id' => [
                'required',
                new CheckPromotionPurchase($this->tickets, $this->movie_time_id),
                new HasPurchasePoints($this->tickets)
            ],
            'tickets.*.movie_time_tariff_id' => 'required|exists:movie_time_tariff,id',
            'tickets.*.quantity' => 'required|numeric|min:1',
            'tickets.*.type' => 'required|in:NORMAL,PROMOCION,PREMIO,CODIGO',
            'tickets.*.codes' => ['array', new CheckPromotionByCode($this->tickets)]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['message' => $validator->errors()->first()], 422)
        );
    }
}
