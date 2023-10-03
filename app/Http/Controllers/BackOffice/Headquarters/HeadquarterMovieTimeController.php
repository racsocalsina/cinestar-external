<?php


namespace App\Http\Controllers\BackOffice\Headquarters;


use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterMovieTimeResource;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\Repositories\Interfaces\MovieTimeRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class HeadquarterMovieTimeController extends Controller
{
    use ApiResponser;

    private $movieTimeRepository;

    public function __construct(MovieTimeRepositoryInterface $movieTimeRepository)
    {
        $this->movieTimeRepository = $movieTimeRepository;
    }

    public function index(Request $request, Headquarter $headquarter)
    {
        $data = $this->movieTimeRepository->searchMovieTimeOfHeadquarter($request->all(), $headquarter);
        return HeadquarterMovieTimeResource::collection($data)->additional(['status' => 200]);
    }

}
