<?php


namespace App\Models\ProductTypes\Repositories\Interfaces;


interface ProductTypeRepositoryInterface
{
    public function queryable();
    public function sync($body, $headquarter = null);
    public function allByHeadquarter($headquarterId, $byCombo = false);
    public function allByHeadquarterPromotion($headquarterId, $movieTimeId = null);
    public function allByType($byCombo = false);
}
