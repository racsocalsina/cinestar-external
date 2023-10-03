<?php


namespace App\Http\Requests\API\Cards;


use App\Models\Cards\Card;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class DestroyCardRequest extends FormRequest
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
        if (!$validator->fails()) {
            $this->checkOwner();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkOwner()
    {
        $card = Card::where('token', $this->token)->first();

        if ($card == null) {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.cards.not_exist')], 422)
            );
        }

        if ($card->user_id != Auth::user()->id) {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.cards.not_owner')], 422)
            );
        }
    }
}
