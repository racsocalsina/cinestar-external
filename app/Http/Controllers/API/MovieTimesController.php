<?php


namespace App\Http\Controllers\API;

use App\Enums\TariffType;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Consumer\MovieTimes\HeadquarterMovieFormatResource;
use App\Http\Resources\Consumer\MovieTimeTariffs\MovieTimeTariffResource;
use App\Http\Resources\Consumer\MovieTimes\MovieTimeDateResource;
use App\Models\MovieTimes\MovieTime;
use App\Models\MovieTimes\Repositories\Interfaces\MovieTimeRepositoryInterface;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\SearchableRules\MovieTimeSearchableRule;
use App\Services\Searchable\Searchable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieTimesController extends ApiController
{
    private $movieTimeRepository;
    private $promotionRepository;

    public function __construct(MovieTimeRepositoryInterface $movieTimeRepository, Searchable $searchableService,
                                TicketPromotionRepositoryInterface $promotionRepository)
    {
        $this->movieTimeRepository = $movieTimeRepository;
        $this->searchableService = $searchableService;
        $this->promotionRepository = $promotionRepository;
    }

    public function listMovieTimes(Request $request)
    {
        $query = MovieTime::with('headquarter')
        ->where('active', 1)
        ->where('start_at', '>', Carbon::now())
        ->whereHas('movie_time_tariffs', function ($query) {
            $query->where('active', 1);
        });

        $this->searchableService->applyArray($query, new MovieTimeSearchableRule(), $request->all());
        $query->orderBy('time_start');

        if ($request->has('limit')) {
            $listMovieTimes = $query->limit($request->limit)->get();
        } else {
            $listMovieTimes = $query->get();
        }

        $movie_times = $this->groupMovieTimes($listMovieTimes);
        return $this->successResponse(HeadquarterMovieFormatResource::collection(array_values($movie_times)));
    }

    public function listTariffs(int $id)
    {
        $movie_time = MovieTime::find($id);

        // Get movie-time-tariffs
        $movieTimeTariffs = MovieTimeTariff::with('movie_tariff')
            ->where('movie_time_id', $movie_time->id)
            ->visibleTariffs()
            ->get();

        $promotion = $this->promotionRepository->promotionForTariff($movie_time);
        if ($promotion) {
            foreach ($movieTimeTariffs as $item) {
                $value = $item->replicate();
                $value->id = $item->id;
                if ($promotion->tariff_type == TariffType::PLANA) {
                    [$amount, $prices] = $this->promotionRepository->aplicatePromotion($value, $promotion);
                    $value->price = $amount;
                    $value->ticket_promotion_id = $promotion->id;
                }else if ($promotion->tariff_type == $value->movie_tariff->remote_funtar){
                    [$amount, $prices] = $this->promotionRepository->aplicatePromotion($value, $promotion);
                    $value->price = $amount;
                    $value->ticket_promotion_id = $promotion->id;
                }
                $movieTimeTariffs[] = $value;

            }

        }

        // Get ticket-promotions
        $ticketPromotions = $this->promotionRepository->listByMovieTime($movie_time);
        foreach ($ticketPromotions as $item) {

            $tariff = MovieTimeTariff::whereHas('movie_tariff', function ($query) use ($item) {
                $query->where('remote_funtar', $item->type_tariff);
            })->where('movie_time_id', $movie_time->id)->first();

            [$amount, $prices] = $this->promotionRepository->aplicatePromotion($tariff, $item);
            $item->price = $amount;
            $item->movie_time_tariff_id = $tariff->id;
            $item->ticket_promotion_id = $item->id;

            $movieTimeTariffs[] = $item;
        }

        // Merge
        return MovieTimeTariffResource::collection($movieTimeTariffs);
    }

    public function groupMovieTimes($listMovieTimes)
    {

        return $listMovieTimes
            ->where('headquarter.status', true)
            ->groupBy(['headquarter.name'])
            ->map(function ($value, $index) {
                return [
                    'name' => $index,
                    'movie_times' => $value
                ];
            })
            ->sortBy('name')
            ->toArray();
    }

    public function listMovieTimesDates(Request $request)
    {

        $query = MovieTime::where('active', 1)
            ->where('start_at', '>', Carbon::now())
            ->where('headquarter_id', '=', $request->headquarter_id)
            ->whereHas('movie_time_tariffs', function ($query) {
                $query->where('active', 1);
            })
            ->select('date_start')
            ->distinct('date_start')
            ->orderBy('date_start')
            ->limit(7)
            ->get();

        return $this->successResponse(MovieTimeDateResource::collection($query));
    }
}
