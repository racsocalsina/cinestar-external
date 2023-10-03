<?php


namespace App\Http\Requests\BackOffice\Roles;


use App\Enums\GlobalEnum;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\SearchableRules\AdminSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleDeleteRequest extends FormRequest
{
    use ApiResponser;

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
        return [];
    }

    public function withValidator($validator)
    {
        if(!$validator->fails())
        {
            $this->superAdminCannotBeDeleted();
            $this->checkIfExistUsersWithThisRole();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function superAdminCannotBeDeleted()
    {
        if($this->route('role')->name == GlobalEnum::ROLE_NAME_SUPER_ADMIN)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.roles.super_admin_cannot_be_delete')], 422)
            );
    }

    private function checkIfExistUsersWithThisRole()
    {

        $query = $this->adminRepository->queryable();
        $this->searchableService->applyArray($query, new AdminSearchableRule(), [
            'role' => $this->route('role')->name
        ]);
        $exists = $query->count() > 0;

        if($exists)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.roles.delete_admins_related')], 422)
            );
    }

}
