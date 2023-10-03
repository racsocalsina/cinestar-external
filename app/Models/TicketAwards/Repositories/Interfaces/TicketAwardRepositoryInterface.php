<?php


namespace App\Models\TicketAwards\Repositories\Interfaces;


interface TicketAwardRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null);
    public function searchBO($request);
    public function getData();
    public function valid($request);
    public function allForApi();
    public function update($data, $request);
}
