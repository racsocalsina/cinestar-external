<?php


namespace App\Http\Controllers\BackOffice\Customers;

use App\Exports\CustomersExport;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Customers\CustomerResource;
use App\Models\Customers\Customer;
use App\Models\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Roles\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\TypeDocuments\Repositories\Interfaces\DocumentTypeRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponser;

    private $repository;
    private $userRepository;
    private $roleRepository;
    private $documentTypeRepository;
    private $headquarterRepository;

    public function __construct(
        Customer $repository,
        CustomerRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        DocumentTypeRepositoryInterface $documentTypeRepository,
        HeadquarterRepositoryInterface $headquarterRepository
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->headquarterRepository = $headquarterRepository;

        $this->middleware('permission:read-reports', ['only' => ['index', 'export']]);
    }

    public function index(Request $request)
    {
        $data = $this->userRepository->search($request->all());
        return CustomerResource::collection($data)->additional(['status' => 200]);
    }

    public function export(Request $request)
    {
        return ApiResponse::excel(new CustomersExport, 'clientes');
    }

    public function ranking(Request $request)
    {
        $data = $this->userRepository->ranking($request->all());
        return CustomerResource::collection($data)->additional(['status' => 200]);
    }


}
