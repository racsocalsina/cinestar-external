<?php


namespace App\Models\MovieTimes\Repositories\Interfaces;


use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;

interface MovieTimeRepositoryInterface
{
    public function listMovieTimes();
    public function updateGraph($data);
    public function searchMovieTimeOfHeadquarter(array $params, Headquarter $headquarter);
    public function update(MovieTime $model, $body);
    public function sync($body, $syncHeadquarter = null);
    public function getSchedules($body);
}
