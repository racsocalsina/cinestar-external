<?php


namespace App\Http\Requests\BackOffice\MovieFormats;


use App\Enums\GlobalEnum;
use App\Models\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovieFormatRequest extends FormRequest
{
    use ApiResponser;

    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description' => 'nullable|string|max:100',
            'status'      => 'required|bool',
        ];

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $rules['name'] = 'required|string|max:20|unique:movie_formats,name,' . $this->route('movie_format')->id;
            $rules['short'] = 'required|string|max:10|unique:movie_formats,short,' . $this->route('movie_format')->id;
        } else if ($this->getMethod() === 'POST') {
            $rules['name'] = 'required|string|max:20|unique:movie_formats,name';
            $rules['short'] = 'required|string|max:10|unique:movie_formats,short';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
