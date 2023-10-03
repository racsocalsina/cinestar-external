<?php

namespace App\Http\Requests\Consumer\Movies;

use App\Enums\TradeName;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MovieListRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $startAt = $this->input('start_at');

            if ($startAt) {
                $today = now()->format('Y-m-d');

                if ($startAt < $today) {
                    $validator->errors()->add('start_at', 'La fecha de inicio debe ser mayor o igual a la fecha actual.');
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
