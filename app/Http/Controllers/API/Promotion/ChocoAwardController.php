<?php


namespace App\Http\Controllers\API\Promotion;

use App\Http\Controllers\ApiController;
use App\Http\Resources\API\ChocoAward\ChocoAwardResource;
use App\Models\ChocoAwards\Repositories\Interfaces\ChocoAwardRepositoryInterface;
use App\Models\Movies\Repositories\Interfaces\MovieValidPromotionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ChocoAwardController extends ApiController
{
    protected $repository;
    protected $movieValidPromotionRepository;

    public function __construct(ChocoAwardRepositoryInterface $chocoAwardRepository,
                                MovieValidPromotionRepositoryInterface $movieValidPromotionRepository)
    {
        $this->repository = $chocoAwardRepository;
        $this->movieValidPromotionRepository = $movieValidPromotionRepository;
    }

    public function index(Request $request)
    {
        $data = $this->repository->getData();
        return ChocoAwardResource::collection($data)->additional(['status' => 200]);
    }

    public function valid(Request $request)
    {
        try {
            $request->validate([
                'headquarter_id' => 'required|exists:headquarters,id',
                'choco_award_id' => 'required|exists:choco_awards,id',
                'purchase_id' => 'sometimes|exists:purchases,id',
                'quantity' => 'required|int|min:1',
                'sweets' => 'required|array',
                'sweets.*.id' => 'required',
                'sweets.*.quantity' => 'required|int',
                'sweets.*.type' => 'required'
            ]);

            $result = $this->repository->valid($request);
            return $this->successResponse($result);
        } catch (\Exception $e) {
            return $this->responseMessageFail($e->getMessage(), [], Response::HTTP_BAD_REQUEST);
        }
    }

}
