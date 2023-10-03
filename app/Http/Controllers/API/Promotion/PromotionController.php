<?php


namespace App\Http\Controllers\API\Promotion;

use App\Enums\PromotionTypes;
use App\Http\Controllers\ApiController;
use App\Http\Resources\PromotionResource;
use App\Models\ChocoPromotions\ChocoPromotion;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends ApiController
{
    protected $ticketPromotion;
    protected $repository;

    public function __construct(TicketPromotionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $t_promotions = TicketPromotion::get();
        $c_promotions = ChocoPromotion::get();
       return PromotionResource::collection($t_promotions->merge($c_promotions))->additional(['status' => 200]);
    }

    public function consultCode($code, Request $request)
    {
        try {
            $data = $this->repository->consultPromotionByCode($request, $code);
            $tariff = MovieTimeTariff::whereHas('movie_tariff', function ($query) use ($data) {
                $query->where('remote_funtar', $data->tariff_type);
            })->where('movie_time_id', $request->movie_time_id)->first();
            [$amount, $prices] = $this->repository->aplicatePromotion($tariff, $data);
            return $this->successResponse([
                'id' => $data->id,
                'code' => $data->code,
                'name' => $data->name,
                'points' => null,
                'tickets_number' => $data->tickets_number,
                'online_price' => $amount,
                'movie_time_tariff_id' => $tariff->id,
                'code_promotion' => $code,
                'type' => PromotionTypes::CODIGO
            ]);
        } catch (\Exception $e) {
            return $this->responseMessageFail($e->getMessage(), [], Response::HTTP_BAD_REQUEST);
        }
    }

}
