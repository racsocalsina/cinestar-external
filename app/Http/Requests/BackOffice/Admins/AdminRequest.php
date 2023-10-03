<?php


namespace App\Http\Requests\BackOffice\Admins;

use App\Enums\GlobalEnum;
use App\Enums\RoleEnum;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\SearchableRules\AdminSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\Requests\AdminRequest as AdminRequestTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    use ApiResponser, AdminRequestTrait;

    private $adminRepository;
    private $searchableService;

    public function __construct(
        AdminRepositoryInterface $adminRepository,
        Searchable $searchableService
    )
    {
        $this->adminRepository = $adminRepository;
        $this->searchableService = $searchableService;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name'             => 'required|string|max:50',
            'lastname'         => 'required|string|max:50',
            'status'           => 'required|bool',
            'role'             => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where(function ($query) {
                    return $query->where('name', '<>', RoleEnum::SUPER_ADMIN);
                }),
            ],
            'document_type_id' => 'required|int|exists:document_types,id',
            'headquarter_id'   => 'sometimes|int|exists:headquarters,id',
            'entry_date'       => 'required|date|date_format:Y-m-d',
        ];

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {

            $rules['email'] = 'required|string|max:50|email|unique:admins,email,' . $this->route('user')->id;
            $rules['document_number'] = 'required|string|max:30|unique:admins,document_number,' . $this->route('user')->id;
            $rules['password'] = 'nullable';

        } else if ($this->getMethod() === 'POST') {

            $rules['email'] = 'required|string|max:50|email|unique:admins,email';
            $rules['document_number'] = 'required|string|max:30|unique:admins,document_number';
            $rules['password'] = 'required';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $this->afterValidations();
            //$this->checkSuperAdminRole();
            //$this->checkSuperAdminStatusRequest();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function afterValidations(){
        if($this->role != RoleEnum::MARKETING){
            if(!isset($this->headquarter_id) || $this->headquarter_id == null){
                throw new HttpResponseException(
                    $this->errorResponse(['status' => 422, 'message' => __('app.admins.headquarter_id_is_required_for_role')], 422)
                );
            }
        }
    }

    private function checkSuperAdminRole()
    {
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {

            // check if is super-admin
            $role = $this->route('user')->roles()->first();

            if (is_null($role))
                return true;

            if ($role->name != GlobalEnum::ROLE_NAME_SUPER_ADMIN)
                return true;

            // check if is the only existing super-admin and the role will change
            $query = $this->adminRepository->queryable();
            $this->searchableService->applyArray($query, new AdminSearchableRule(), [
                'role' => GlobalEnum::ROLE_NAME_SUPER_ADMIN
            ]);
            $onlyOneSuperAdmin = $query->count();

            if ($onlyOneSuperAdmin > 1)
                return true;

            $roleHasChanged = $role->name != $this->role;

            if (!$roleHasChanged) {
                return true;
            }

            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.admins.update_at_least_one_super_admin_must_exist')], 422)
            );
        }
    }

    private function checkSuperAdminStatusRequest()
    {
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {

            $pass = $this->checkSuperAdminStatus($this->adminRepository, $this->route('user'), $this->status);

            if(is_array($pass))
                throw new HttpResponseException(
                    $this->errorResponse($pass, 422)
                );

        }
    }
}
