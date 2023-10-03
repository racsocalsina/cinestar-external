<?php


namespace App\Http\Requests\Purchase;


use App\Rules\CheckRoomCapacity;
use App\Rules\Purchase\CheckInternalIsAvailable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePurchaseRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'movie_time_id' => [
                'required',
                'exists:movie_times,id',
                new CheckRoomCapacity(request('movie_time_id'), request('tickets'), $this->route('id')),
                new CheckInternalIsAvailable($this->route('id'))
            ],
            'tickets' => 'required|array|min:1',
            'tickets.*.movie_time_tariff_id' => 'required|exists:movie_time_tariff,id',
            'tickets.*.quantity' => 'required|integer|min:1'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['message' => $validator->errors()->first()], 422)
        );
    }

}
