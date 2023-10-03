<?php


namespace App\Http\Controllers\BackOffice\Roles;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Roles\RoleDeleteRequest;
use App\Http\Requests\BackOffice\Roles\RoleRequest;
use App\Http\Resources\BackOffice\Modules\ModulePermissionCollection;
use App\Http\Resources\BackOffice\Roles\RoleResource;
use App\Models\Modules\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Models\Roles\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Roles\Role;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponser;

    private $roleRepository;
    private $moduleRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        ModuleRepositoryInterface $moduleRepository
    )
    {
        $this->roleRepository = $roleRepository;
        $this->moduleRepository = $moduleRepository;

        $this->middleware('permission:read-role', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-role', ['only' => ['store']]);
        $this->middleware('permission:update-role', ['only' => ['update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->roleRepository->search($request->all(), false);
        return RoleResource::collection($data)->additional(['status' => 200]);
    }

    public function parameters()
    {
        $modules = $this->moduleRepository->getModulesWithPermissionsRelated();
        $data = [
            'modules' => ModulePermissionCollection::collection($modules)
        ];
        return $this->success($data);
    }

    public function store(RoleRequest $request, Role $role)
    {
        $role = $this->roleRepository->create($request->validated());

        return $this->created(
            new RoleResource($role)
        );
    }

    public function show(Role $role)
    {
        return $this->successResponse(
            new RoleResource($role)
        );
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role = $this->roleRepository->update($role, $request->validated());

        return $this->successResponse(
            new RoleResource($role)
        );
    }

    public function destroy(RoleDeleteRequest $request, Role $role)
    {
        $this->roleRepository->delete($role);
        return $this->successResponse([]);
    }

}
