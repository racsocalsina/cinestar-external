<?php


namespace App\Http\Controllers\API\Promotion;

use App\Http\Controllers\ApiController;
use App\Http\Resources\API\TicketAward\TicketAwardResource;
use App\Models\Movies\Repositories\Interfaces\MovieValidPromotionRepositoryInterface;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\TicketAwards\Repositories\Interfaces\TicketAwardRepositoryInterface;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TicketAwardController extends ApiController
{
    protected $repository;
    protected $movieValidPromotionRepository;
    private $promotionRepository;

    public function __construct(TicketAwardRepositoryInterface $ticketAwardRepository,
                                MovieValidPromotionRepositoryInterface $movieValidPromotionRepository,
                                TicketPromotionRepositoryInterface $promotionRepository)
    {
        $this->repository = $ticketAwardRepository;
        $this->movieValidPromotionRepository = $movieValidPromotionRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function index(Request $request)
    {
        $request->validate(['movie_time_id' => 'required|exists:movie_times,id']);

        $status = $this->checkMoviePromotions($request->movie_time_id);
        if ($status) {
            $data = $this->repository->getData();
            foreach ($data as $item) {
                $tariff = MovieTimeTariff::whereHas('movie_tariff', function ($query) use ($item) {
                    $query->where('remote_funtar', $item->promotion->tariff_type);
                })->where('movie_time_id', $request->movie_time_id)->first();
                [$amount, $prices] = $this->promotionRepository->aplicatePromotion($tariff, $item->promotion);
                $item->price = $amount;
                $item->movie_time_tariff_id = $tariff->id;

            };
            return TicketAwardResource::collection($data)->additional(['status' => 200]);
        }

        return $this->responseMessageFail('Esta película se encuentra restringida para el canje de promociones');

    }

    public function valid(Request $request)
    {
        $request->validate([
            'movie_time_id' => 'required|exists:movie_times,id',
            'awards' => 'required|array',
            'awards.*.ticket_award_id' => 'required|exists:ticket_awards,id',
            'awards.*.quantity' => 'required|int',
        ]);
        try {

            $status = $this->checkMoviePromotions($request->movie_time_id);
            if ($status) {
                $result = $this->repository->valid($request);
                return $this->successResponse($result);
            }

            return $this->responseMessageFail('Esta película se encuentra restringida para el canje de promociones');
        } catch (\Exception $e) {
            return $this->responseMessageFail($e->getMessage(), [], Response::HTTP_BAD_REQUEST);
        }
    }

    private function checkMoviePromotions($movie_time_id)
    {
        return $this->movieValidPromotionRepository->checkMovieIsValidForPromotions($movie_time_id);
    }

}
