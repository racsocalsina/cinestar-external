<?php


namespace App\Http\Controllers\BackOffice\Headquarters;


use App\Enums\BusinessName;
use App\Enums\TradeName;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Headquarters\HeadquarterDeleteRequest;
use App\Http\Requests\BackOffice\Headquarters\HeadquarterRequest;
use App\Http\Resources\BackOffice\Cities\CityResource;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterResource;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Models\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Models\HeadquarterImages\Repositories\Interfaces\HeadquarterImageRepositoryInterface;
use App\Models\Headquarters\Headquarter;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;
use App\Models\SyncLogs\Repositories\Interfaces\SyncLogRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Controllers\ChangeStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeadquarterController extends Controller
{
    use ApiResponser, ChangeStatus;

    private $repository;
    private $headquarterRepository;
    private $cityRepository;
    private $movieFormatRepository;
    private $headquarterImageRepository;
    private $nameCache;
    private $timeCache;
    private $syncLogRepository;

    public function __construct(
        Headquarter $repository,
        HeadquarterRepositoryInterface $headquarterRepository,
        CityRepositoryInterface $cityRepository,
        MovieFormatRepositoryInterface $movieFormatRepository,
        HeadquarterImageRepositoryInterface $headquarterImageRepository,
        SyncLogRepositoryInterface $syncLogRepository
    )
    {
        $this->nameCache = config('cache-redis.headquarter:migrate-data.identify');
        $this->timeCache = config('cache-redis.headquarter:migrate-data.time');
        $this->repository = $repository;
        $this->headquarterRepository = $headquarterRepository;
        $this->syncLogRepository = $syncLogRepository;
        $this->cityRepository = $cityRepository;
        $this->movieFormatRepository = $movieFormatRepository;
        $this->headquarterImageRepository = $headquarterImageRepository;

        $this->middleware('permission:read-headquarter', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-headquarter', ['only' => ['store']]);
        $this->middleware('permission:update-headquarter', ['only' => ['update']]);
        $this->middleware('permission:delete-headquarter', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->headquarterRepository->search($request->all());
        return HeadquarterResource::collection($data)->additional(['status' => 200]);
    }

    public function parameters()
    {
        $cities = $this->cityRepository->listCities();
        $movieFormats = $this->movieFormatRepository->all();

        $data = [
            'cities'         => CityResource::collection($cities),
            'movie_formats'  => ListCollection::collection($movieFormats),
            'business_names' => BusinessName::ALL_DATA,
            'trade_names'    => TradeName::ALL_DATA,
        ];
        return $this->success($data);
    }

    public function store(HeadquarterRequest $request)
    {
        try {
            DB::beginTransaction();

            $fields = $request->validated();
            unset($fields['movie_formats']);

            $params = [
                'fields'        => $fields,
                'movie_formats' => explode(',', $request->movie_formats)
            ];

            $data = $this->headquarterRepository->create($params);
            $this->headquarterImageRepository->saveImages($data, $request->file('files'));
            $response = $this->created(new HeadquarterResource($data));
            //$jobs = new SyncProcess($data);
            //dispatch($jobs);

            DB::commit();
            return $this->successResponse($response, 201);
        } catch (\Exception $exception) {
            $message = 'Error al crear la sede. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        return $response;
    }

    public function show(Headquarter $headquarters)
    {
        return $this->successResponse(
            new HeadquarterResource($headquarters)
        );
    }

    public function update(HeadquarterRequest $request, Headquarter $headquarters)
    {
        try {
            DB::beginTransaction();

            $data = $this->headquarterRepository->update($headquarters, $request->validated());

            $response = $this->successResponse(
                new HeadquarterResource($data)
            );
        } catch (\Exception $exception) {
            $message = 'Error al actualizar la sede. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function destroy(HeadquarterDeleteRequest $request, Headquarter $headquarters)
    {
        $this->headquarterRepository->delete($headquarters);
        return $this->successResponse([]);
    }

}
