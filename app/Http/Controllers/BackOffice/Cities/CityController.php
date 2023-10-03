<?php


namespace App\Http\Controllers\BackOffice\Cities;


use App\Enums\TradeName;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Cities\CityDeleteRequest;
use App\Http\Requests\BackOffice\Cities\CityRequest;
use App\Http\Resources\BackOffice\Cities\CityResource;
use App\Models\Cities\City;
use App\Models\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ApiResponser;

    private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;

        $this->middleware('permission:read-city', ['only' => ['index']]);
        $this->middleware('permission:create-city', ['only' => ['store']]);
        $this->middleware('permission:update-city', ['only' => ['update']]);
        $this->middleware('permission:delete-city', ['only' => ['destroy']]);
    }

    public function parameters()
    {
        $data = [
            'trade_names' => TradeName::ALL_DATA,
        ];
        return $this->success($data);
    }

    public function index(Request $request)
    {
        $data = $this->cityRepository->search($request->all(), false);
        return CityResource::collection($data)->additional(['status' => 200]);
    }

    public function store(CityRequest $request, City $city)
    {
        $data = $this->cityRepository->create($request->validated());

        return $this->created(
            new CityResource($data)
        );
    }

    public function update(CityRequest $request, City $city)
    {
        $data = $this->cityRepository->update($city, $request->validated());

        return $this->successResponse(
            new CityResource($data)
        );
    }

    public function destroy(CityDeleteRequest $request, City $city)
    {
        $this->cityRepository->delete($city);
        return $this->successResponse([]);
    }

}
