<?php


namespace App\Http\Requests\BackOffice\ContentManagements;


use App\Enums\TradeName;
use App\Rules\ContentManagement\ContentManagementCheckPartnerItems;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContentManagementPartnerStoreRequest extends FormRequest
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
            'sub_title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3000',
            'file' => 'nullable|file|mimes:pdf',
            'terms' => 'required',
            'benefits' => [
                'nullable',
                'array',
                new ContentManagementCheckPartnerItems()
            ]
        ];
    }

    public function attributes()
    {
        return [
            'file'  => 'Archivo de termino y condiciones',
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
