<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\ApiController;
use App\Http\Resources\ChocoPromotions\ChocoPromotionResource;
use App\Models\ChocoPromotions\Repositories\Interfaces\ChocoPromotionRepositoryInterface;
use App\Services\Searchable\Searchable;
use Illuminate\Http\Request;

class ChocoPromotionController extends ApiController
{
    private $chocoPromotionRepository;
    private $searchableService;

    public function __construct(
        ChocoPromotionRepositoryInterface $chocoPromotionRepository,
        Searchable $searchableService
    )
    {
        $this->chocoPromotionRepository = $chocoPromotionRepository;
        $this->searchableService = $searchableService;
    }

    public function listAll(Request $request) {
        $res = $this->chocoPromotionRepository->listAll($request);
        return $this->successResponse(ChocoPromotionResource::collection($res));
    }
}

