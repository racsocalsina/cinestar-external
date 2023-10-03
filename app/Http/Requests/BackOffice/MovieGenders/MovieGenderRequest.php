<?php


namespace App\Http\Requests\BackOffice\MovieGenders;


use App\Models\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovieGenderRequest extends FormRequest
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
            'status' => 'required'
        ];

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $rules['name'] = 'required|string|max:30|alpha_spaces|unique:movie_genders,name,' . $this->route('movie_gender')->id;
            $rules['short'] = 'required|string|max:30|alpha_spaces|unique:movie_genders,short,' . $this->route('movie_gender')->id;
        } else if ($this->getMethod() === 'POST') {
            $rules['name'] = 'required|string|max:30|alpha_spaces|unique:movie_genders,name';
            $rules['short'] = 'required|string|max:30|alpha_spaces|unique:movie_genders,short';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }
}
