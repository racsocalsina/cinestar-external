<?php


namespace App\Models\ProductPrices\Repositories\Interfaces;

interface ProductPriceRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
}
