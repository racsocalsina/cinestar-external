<?php


namespace App\Http\Controllers\BackOffice\Admins;


use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Admins\AdminDeleteRequest;
use App\Http\Requests\BackOffice\Admins\AdminRequest;
use App\Http\Resources\BackOffice\Admins\AdminResource;
use App\Http\Resources\BackOffice\Roles\RoleCollection;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Models\Admins\Admin;
use App\Models\Admins\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Roles\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\TypeDocuments\Repositories\Interfaces\DocumentTypeRepositoryInterface;
use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Requests\AdminRequest as AdminRequestTrait;

class AdminController extends Controller
{
    use ApiResponser, AdminRequestTrait;

    private $repository;
    private $adminRepository;
    private $roleRepository;
    private $documentTypeRepository;
    private $headquarterRepository;

    public function __construct(
        Admin $repository,
        AdminRepositoryInterface $adminRepository,
        RoleRepositoryInterface $roleRepository,
        DocumentTypeRepositoryInterface $documentTypeRepository,
        HeadquarterRepositoryInterface $headquarterRepository
    )
    {
        $this->repository = $repository;
        $this->adminRepository = $adminRepository;
        $this->roleRepository = $roleRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->headquarterRepository = $headquarterRepository;

        $this->middleware('permission:read-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->adminRepository->search($request->all());
        return AdminResource::collection($data)->additional(['status' => 200]);
    }

    public function parameters()
    {
        $roles = $this->roleRepository->all()->filter(function($role) {
            return $role->name != RoleEnum::SUPER_ADMIN;
        });
        $documentTypes = $this->documentTypeRepository->listTypeDocuments();
        $headquarters = $this->headquarterRepository->all();

        $data = [
            'roles'          => RoleCollection::collection($roles),
            'document_types' => ListCollection::collection($documentTypes),
            'headquarters'   => ListCollection::collection($headquarters),
        ];
        return $this->success($data);
    }

    public function store(AdminRequest $request, Admin $admin)
    {
        try {
            DB::beginTransaction();

            $admin = $this->adminRepository->create($request->validated());

            $response = $this->created(
                new AdminResource($admin)
            );
        } catch (\Exception $exception) {
            $message = 'Error al crear el usuario. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function show(Admin $user)
    {
        return $this->successResponse(
            new AdminResource($user)
        );
    }

    public function update(AdminRequest $request, Admin $user)
    {
        try {
            DB::beginTransaction();

            $user = $this->adminRepository->update($user, $request->validated());

            $response = $this->successResponse(
                new AdminResource($user)
            );
        } catch (\Exception $exception) {
            $message = 'Error al actualizar el usuario. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function destroy(AdminDeleteRequest $request, Admin $user)
    {
        $this->adminRepository->delete($user);
        return $this->successResponse([]);
    }

    public function toggleStatus($id, Request $req)
    {
        $model = $this->repository->findOrFail($id);

        if($model->hasRole(RoleEnum::SUPER_ADMIN))
            throw new HttpResponseException(
                $this->errorResponse(['status' => 422, 'message' => __('app.admins.cannot_update_super_admin')], 422)
            );

        if($model instanceof ActivatableInterface){
            $model->toggleActive();
            return response()->json(['new_status'=>$model->isActive()]);
        }
        return response()->json($model,412);
    }

}
