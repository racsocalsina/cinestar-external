<?php


namespace App\Http\Requests\BackOffice\Roles;


use App\Enums\GlobalEnum;
use App\Models\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
            'permissions' => 'required|array'
        ];

        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $rules['name'] = 'required|string|max:30|unique:roles,name,' . $this->route('role')->id;
            $rules['display_name'] = 'required|string|max:30|unique:roles,display_name,' . $this->route('role')->id;
        } else if ($this->getMethod() === 'POST') {
            $rules['name'] = 'required|string|max:30|unique:roles,name';
            $rules['display_name'] = 'required|string|max:30|unique:roles,display_name';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if(!$validator->fails())
        {
            $this->superAdminCannotBeEdited();
            $this->checkPermissionsExist();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function superAdminCannotBeEdited()
    {
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {

            $role = $this->route('role');

            if($role->name == GlobalEnum::ROLE_NAME_SUPER_ADMIN)
                throw new HttpResponseException(
                    $this->errorResponse(['status' => 422, 'message' => __('app.roles.super_admin_cannot_be_update')], 422)
                );
        }
    }

    private function checkPermissionsExist()
    {
        $permissionsEntered = $this->permissions;
        $permissions = $this->permissionRepository->getAllByNames($permissionsEntered);

        if($permissions->count() == 0)
        {
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.roles.permissions_dont_exist')], 422)
            );
        }

        foreach ($permissionsEntered as $item)
        {
            $exists = $permissions->filter(function ($permission) use ($item) {
                    return $permission->name == $item;
                })->first() != null;

            if(!$exists)
                throw new HttpResponseException(
                    $this->errorResponse(['status' => 422, 'message' => __('app.roles.permission_does_not_exist', ['name' => $item])], 422)
                );
        }
    }
}
