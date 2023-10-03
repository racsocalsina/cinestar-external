<?php


namespace App\Models\ChocoAwards\Repositories\Interfaces;


interface ChocoAwardRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
    public function searchBO($request);
    public function getData();
    public function valid($request);
    public function allForApi();
    public function update($data, $request);
}
