<?php


namespace App\Http\Controllers\API\Share;


use App\Http\Controllers\ApiController;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;

class MovieFormatsController extends ApiController
{
    private $movieFormatRepository;

    public function __construct(MovieFormatRepositoryInterface $movieFormatRepository)
    {
        $this->movieFormatRepository = $movieFormatRepository;
    }

    public function index()
    {
        $res = $this->movieFormatRepository->all();
        return $this->successResponse($res);
    }
}
