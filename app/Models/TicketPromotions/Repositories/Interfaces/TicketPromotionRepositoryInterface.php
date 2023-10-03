<?php


namespace App\Models\TicketPromotions\Repositories\Interfaces;


interface TicketPromotionRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
    public function listByMovieTime($movie_time);
    public function searchBO($request);
    public function searchPromotion($request);
    public function consultPromotionByCode($request, $code);
    public function promotionByCode($request, $code);
    public function addTickets($promotion, $tickets, $movie_time, $quantity, $code = null, $points = null);
    public function aplicatePromotion($motive_time_tariff, $promotion);
    public function tariffPrice($motive_time_tariff);
    public function promotionForTariff($movie_time);
    public function allForApi();
    public function update($data, $request);
}
