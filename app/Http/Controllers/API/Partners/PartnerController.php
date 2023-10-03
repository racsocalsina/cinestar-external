<?php


namespace App\Http\Controllers\API\Partners;


use App\Http\Controllers\Controller;
use App\Http\Resources\API\Partners\AwardResource;
use App\Http\Resources\API\Partners\PromotionResource;
use App\Models\ChocoAwards\Repositories\Interfaces\ChocoAwardRepositoryInterface;
use App\Models\ChocoPromotions\Repositories\Interfaces\ChocoPromotionRepositoryInterface;
use App\Models\PointsHistory\Repositories\Interfaces\PointHistoryRepositoryInterface;
use App\Models\TicketAwards\Repositories\Interfaces\TicketAwardRepositoryInterface;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Traits\ApiResponser;

class PartnerController  extends Controller
{
    use ApiResponser;

    private PointHistoryRepositoryInterface $pointHistoryRepository;
    private TicketAwardRepositoryInterface $ticketAwardRepository;
    private TicketPromotionRepositoryInterface $ticketPromotionRepository;
    private ChocoAwardRepositoryInterface $chocoAwardRepository;
    private ChocoPromotionRepositoryInterface $chocoPromotionRepository;

    public function __construct(
        TicketAwardRepositoryInterface $ticketAwardRepository,
        TicketPromotionRepositoryInterface $ticketPromotionRepository,
        ChocoAwardRepositoryInterface $chocoAwardRepository,
        ChocoPromotionRepositoryInterface $chocoPromotionRepository
    )
    {
        $this->ticketAwardRepository = $ticketAwardRepository;
        $this->ticketPromotionRepository = $ticketPromotionRepository;
        $this->chocoAwardRepository = $chocoAwardRepository;
        $this->chocoPromotionRepository = $chocoPromotionRepository;
    }

    public function getPromotions()
    {
        $ticketPromotions = $this->ticketPromotionRepository->allForApi();
        $chocoPromotions = $this->chocoPromotionRepository->allForApi();
        $data = $ticketPromotions->merge($chocoPromotions);
        return $this->successResponse(PromotionResource::collection($data));
    }

    public function getAwards()
    {
        $ticketAwards = $this->ticketAwardRepository->allForApi();
        $chocoAwards = $this->chocoAwardRepository->allForApi();
        $data = $ticketAwards->merge($chocoAwards);
        return $this->successResponse(AwardResource::collection($data));
    }
}
