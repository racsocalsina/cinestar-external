<?php


namespace App\Http\Requests\BackOffice\ContentManagements;


use App\Enums\TradeName;
use App\Rules\ContentManagement\ContentManagementCheckCorporateItems;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContentManagementCorporateStoreRequest extends FormRequest
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
            'title' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'services' => [
                'nullable',
                'array',
                new ContentManagementCheckCorporateItems()
            ]
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
