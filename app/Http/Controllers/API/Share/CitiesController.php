<?php


namespace App\Http\Controllers\API\Share;


use App\Http\Controllers\ApiController;
use App\Models\Cities\Repositories\Interfaces\CityRepositoryInterface;
use Illuminate\Http\Request;

class CitiesController extends ApiController
{
    private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        $res = $this->cityRepository->listCities();
        return $this->successResponse($res);
    }
}
