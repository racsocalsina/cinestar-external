<?php


namespace App\Http\Controllers\API\Points;


use App\Helpers\FunctionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Points\CheckPointsRequest;
use App\Models\PointsHistory\Repositories\Interfaces\PointHistoryRepositoryInterface;
use App\Traits\ApiResponser;

class PointController extends Controller
{
    use ApiResponser;

    private PointHistoryRepositoryInterface $pointHistoryRepository;

    public function __construct(PointHistoryRepositoryInterface $pointHistoryRepository)
    {
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    public function checkPoints(CheckPointsRequest $request)
    {
        return $this->successResponse($this->pointHistoryRepository->getCheckPointsData(FunctionHelper::getApiUser(), $request->movie_time_id));
    }
}
