<?php


namespace App\Http\Requests\BackOffice\HeadquarterImages;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\Requests\HeadquarterImageRequest as HeadquarterImageRequestTrait;

class HeadquarterImageRequest extends FormRequest
{
    use ApiResponser, HeadquarterImageRequestTrait;

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
            $this->checkImgs();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkImgs()
    {
        $files = $this->file('files');

        $pass = $this->checkImages($this->route('headquarter'), $files);

        if(is_array($pass))
            throw new HttpResponseException(
                $this->errorResponse($pass, 422)
            );
    }
}
