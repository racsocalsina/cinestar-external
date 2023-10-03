<?php


namespace App\Http\Controllers\BackOffice\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Auth\LoginRequest;
use App\Http\Resources\BackOffice\Admins\AdminLoginResource;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Admins\Repositories\Interfaces\AuthRepositoryInterface;
use App\Models\Modules\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Traits\ApiResponser;

class LoginController extends Controller
{
    use ApiResponser;

    private $authRepository;
    private $moduleRepository;
    private $adminRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        ModuleRepositoryInterface $moduleRepository,
        AdminRepositoryInterface $adminRepository
    )
    {
        $this->authRepository = $authRepository;
        $this->moduleRepository = $moduleRepository;
        $this->adminRepository = $adminRepository;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->adminRepository->getByDocumentNumber($request->document_number, true);
        $tokenResult = $this->authRepository->getTokenResult($user);
        $permissionsPerModule = $this->moduleRepository->getModulesWithPermissionsRelatedByUser($user);

        return $this->successResponse(new AdminLoginResource(
            $user,
            $tokenResult,
            $permissionsPerModule
        ));
    }


}
