<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\ApiController;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Services\Searchable\Searchable;
use App\Traits\ApiResponser;

class TicketPromotionController extends ApiController
{
    use ApiResponser;
    private $ticketPromotionRepository;

    public function __construct(
        TicketPromotionRepositoryInterface $ticketPromotionRepository,
        Searchable $searchableService
    )
    {
        $this->ticketPromotionRepository = $ticketPromotionRepository;
        $this->searchableService = $searchableService;
    }

}
