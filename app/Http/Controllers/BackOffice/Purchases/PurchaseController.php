<?php


namespace App\Http\Controllers\BackOffice\Purchases;

use App\Enums\PurchaseStatus;
use App\Enums\PurchaseStatusTransaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterListResource;
use App\Http\Resources\BackOffice\Movies\MovieListResource;
use App\Http\Resources\BackOffice\Purchases\PurchaseColletionResource;
use App\Http\Resources\BackOffice\Purchases\PurchaseTransactionColletionResource;
use App\Http\Resources\BackOffice\Purchases\PurchasePaymentDataQR;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\Models\MovieTimes\Repositories\Interfaces\MovieTimeRepositoryInterface;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use ApiResponser;

    private $repository;
    private $purchaseRepository;
    private $roleRepository;
    private $headquarterRepository;
    private $movieRepository;
    private $movieTimeRepository;

    public function __construct(
        Purchase $repository,
        PurchaseRepositoryInterface $purchaseRepository,
        HeadquarterRepositoryInterface $headquarterRepository,
        MovieRepositoryInterface $movieRepository,
        MovieTimeRepositoryInterface $movieTimeRepository
    )
    {
        $this->repository = $repository;
        $this->purchaseRepository = $purchaseRepository;
        $this->headquarterRepository = $headquarterRepository;
        $this->movieRepository = $movieRepository;
        $this->movieTimeRepository = $movieTimeRepository;
        $this->middleware('permission:read-reports', ['only' => ['getTotals', 'getParameters']]);
    }

    public function getTotals(Request $request)
    {
        $data = $this->purchaseRepository->getTotalData($request);
        return $this->successResponse($data);
    }

    public function getParameters(Request $request)
    {
        $headquarters = $this->headquarterRepository->all();
        $filter = $request['headquarter_id'] ? ['headquarter_id' => $request['headquarter_id']] : [];
        $movies = $this->movieRepository->all($filter);
        $schedules = $this->movieTimeRepository->getSchedules($request);
        $data = [
            'headquarters'   => HeadquarterListResource::collection($headquarters),
            'movies'  => MovieListResource::collection($movies),
            'schedules'  => $schedules,
        ];
        return $this->success($data);
    }

    public function parameters()
    {
        $movies = $this->movieRepository->all([]);

        $data = [
            'headquarters' => ListCollection::collection($this->headquarterRepository->all()),
            'status' => PurchaseStatus::getAllForBO(),
            'movies'  => MovieListResource::collection($movies),
        ];
        return $this->success($data);
    }

    public function index(Request $request)
    {
        $data = $this->purchaseRepository->searchBO($request->all());
        return PurchaseColletionResource::collection($data)->additional(['status' => 200]);
    }

    public function indexTransaction(Request $request)
    {
        $data = $this->purchaseRepository->transactionSearchBO($request->all());
        return PurchaseTransactionColletionResource::collection($data)->additional(['status' => 200]);
    }

    public function transactionParameters()
    {
        $movies = $this->movieRepository->all([]);

        $data = [
            'headquarters' => ListCollection::collection($this->headquarterRepository->all()),
            'status' => PurchaseStatusTransaction::getAllForBO(),
            'movies'  => MovieListResource::collection($movies)
        ];
        return $this->success($data);
    }

    public function transactionsPerDay()
    {
        $data = [ 'transactionsPerDay' => $this->purchaseRepository->transactionsPerDay()];
        return $this->success($data);
    }

    public function transactionsPerMonth()
    {
        $data = [ 'transactionsPerMonth' => $this->purchaseRepository->transactionsPerMonth()];
        return $this->success($data);
    }

    public function transactionsPerWeek()
    {
        $data = [ 'transactionsPerWeek' => $this->purchaseRepository->transactionsPerWeek()];
        return $this->success($data);
    }

    public function purchasesTransactionPayu(Request $request){
        $data = $this->purchaseRepository->purchasesTransactionPayu($request);
        return $data;
    }
}
