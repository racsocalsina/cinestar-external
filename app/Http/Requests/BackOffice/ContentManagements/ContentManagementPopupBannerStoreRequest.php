<?php


namespace App\Http\Requests\BackOffice\ContentManagements;


use App\Enums\TradeName;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContentManagementPopupBannerStoreRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'trade_name' => 'required|string|in:' . implode(',', TradeName::ALL_VALUES),
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:3000',
            'button_name' => 'string',
            'movie_title' => 'string',
            'popup_title' => 'string',
            'movie_id' => 'integer|exists:movies,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    /**
     * @return array
     */
    protected function validationMessages()
    {
        return $messsages = array(

        );
    }

}
