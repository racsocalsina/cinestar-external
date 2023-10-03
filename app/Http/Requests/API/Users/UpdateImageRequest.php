<?php


namespace App\Http\Requests\API\Users;

use App\Helpers\Helper;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;


class UpdateImageRequest extends FormRequest
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
            $this->checkData();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkData()
    {
        
        $request = $this;

        $maxSize = "3MB";
        $maxSizeInBytes = $this->convertToBytes($maxSize);

        if(!isset($request->image)){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "Falta añadir una foto."], 422)
            );
        }

        if ($request->image->getSize() > $maxSizeInBytes) {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => "La imagen no debe ser mayor de " . $maxSize], 422)
            );
        }

    }

    private function convertToBytes($size)
    {
        $unit = strtoupper(substr($size, -2));
        $size = intval(substr($size, 0, -2));
        switch ($unit) {
            case 'KB':
                return $size * 1024;
            case 'MB':
                return $size * 1024 * 1024;
            case 'GB':
                return $size * 1024 * 1024 * 1024;
            default:
                return $size;
        }
    }
}