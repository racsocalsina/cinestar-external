<?php


namespace App\Http\Controllers\Consumer\Headquarter;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Consumer\Headquarters\HeadquarterResource;
use App\Models\Headquarters\Repositories\Interfaces\ConsumerHeadquarterRepositoryInterface;

class HeadquarterController extends Controller
{

    private ConsumerHeadquarterRepositoryInterface $repository;

    /**
     * HeadquarterController constructor.
     * @param $repository
     */
    public function __construct(ConsumerHeadquarterRepositoryInterface  $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ApiResponse::success(HeadquarterResource::collection($this->repository->all()));
    }
}
