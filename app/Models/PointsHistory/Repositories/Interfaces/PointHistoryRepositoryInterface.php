<?php


namespace App\Models\PointsHistory\Repositories\Interfaces;


use App\Models\Purchases\Purchase;

interface PointHistoryRepositoryInterface
{
    public function store(Purchase $purchase);
    public function getExpiredPoints();
    public function addExpirationPoint($pointHistoryRelated) : void;
    public function getCheckPointsData($user, $movieTimeId);
}
