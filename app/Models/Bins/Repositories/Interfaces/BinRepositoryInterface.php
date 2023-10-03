<?php


namespace App\Models\Bins\Repositories\Interfaces;


interface BinRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
}