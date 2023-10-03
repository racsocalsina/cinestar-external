<?php


namespace App\Http\Requests\BackOffice\Auth;


use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    use ApiResponser;

    private $adminRepository;
    private $user;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'document_number' => 'required|string',
            'password'        => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->checkDocumentNumber();
            $this->checkPassword();
            $this->checkStatus();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkDocumentNumber()
    {
        $this->user = $this->adminRepository->getByDocumentNumber($this->document_number);

        if (!$this->user)
            throw new HttpResponseException($this->unauthorized(__('auth.user_not_registered')));
    }

    private function checkPassword()
    {
        // do the passwords match?
        $match = Hash::check($this->password, $this->user->password);

        if (!$match) {
            throw new HttpResponseException($this->unauthorized(__('auth.password_not_match')));
        }
    }

    private function checkStatus()
    {
        if (!$this->user->status)
            throw new HttpResponseException($this->unauthorized(__('auth.disabled')));
    }

}
