<?php


namespace App\Http\Controllers\BackOffice\Countries;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Countries\CountryRequest;
use App\Http\Resources\BackOffice\Countries\CountryResource;
use App\Models\Countries\Country;
use App\Models\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use ApiResponser;

    private $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;

        $this->middleware('permission:read-country', ['only' => ['index']]);
        $this->middleware('permission:create-country', ['only' => ['store']]);
        $this->middleware('permission:update-country', ['only' => ['update']]);
        $this->middleware('permission:delete-country', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->countryRepository->all();
        return CountryResource::collection($data)->additional(['status' => 200]);
    }

    public function store(CountryRequest $request)
    {
        $data = $this->countryRepository->create($request->all());

        return $this->created(
            new CountryResource($data)
        );
    }

    public function update(CountryRequest $request, Country $country)
    {
        $data = $this->countryRepository->update($country, $request->all());

        return $this->successResponse(
            new CountryResource($data)
        );
    }

    public function destroy(Country $country)
    {
        $this->countryRepository->destroy($country);

        return $this->successResponse([]);
    }
}
