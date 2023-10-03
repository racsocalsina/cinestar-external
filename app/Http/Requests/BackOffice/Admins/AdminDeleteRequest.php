<?php


namespace App\Http\Requests\BackOffice\Admins;


use App\Enums\GlobalEnum;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\SearchableRules\AdminSearchableRule;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminDeleteRequest extends FormRequest
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
            $this->cannotDeleteSuperAdmin();
            //$this->checkSuperAdmin();
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    private function checkSuperAdmin()
    {
        $role = $this->route('user')->roles()->first();

        if(is_null($role))
            return true;

        if($role->name != GlobalEnum::ROLE_NAME_SUPER_ADMIN){
            return true;
        }

        $query = $this->adminRepository->queryable();
        $this->searchableService->applyArray($query, new AdminSearchableRule(), [
            'role' => GlobalEnum::ROLE_NAME_SUPER_ADMIN
        ]);
        $onlyOneSuperAdmin = $query->count() == 1;

        if($onlyOneSuperAdmin == 1)
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.admins.delete_at_least_one_super_admin_must_exist')], 422)
            );
    }

    private function cannotDeleteSuperAdmin() : void
    {
        $role = $this->route('user')->roles()->first();

        if($role->name == GlobalEnum::ROLE_NAME_SUPER_ADMIN){
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.admins.cannot_delete_super_admin')], 422)
            );
        }
    }

}
