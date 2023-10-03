<?php


namespace App\Models\Products\Repositories\Interfaces;


interface SweetRepositoryInterface
{
    public function searchFavoriteApi(array $params);
    public function toggleFavorite($params);
}
