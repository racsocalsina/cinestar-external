<?php


namespace App\Models\ChocoPromotions\Repositories\Interfaces;


interface ChocoPromotionRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
    public function syncProducts($body);
    public function searchBO($request);
    public function allForApi();
    public function update($data, $request);
}
