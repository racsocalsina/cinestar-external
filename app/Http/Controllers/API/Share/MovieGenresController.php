<?php


namespace App\Http\Controllers\API\Share;


use App\Http\Controllers\ApiController;
use App\Models\MovieGenders\Repositories\Interfaces\MovieGenderRepositoryInterface;

class MovieGenresController extends ApiController
{
    private $movieGenderRepository;

    public function __construct(MovieGenderRepositoryInterface $movieGenderRepository)
    {
        $this->movieGenderRepository = $movieGenderRepository;
    }

    public function index()
    {
        $res = $this->movieGenderRepository->listGenders();
        return $this->successResponse($res);
    }
}
