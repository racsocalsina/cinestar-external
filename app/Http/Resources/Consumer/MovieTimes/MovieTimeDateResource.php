<?php

namespace App\Http\Resources\Consumer\MovieTimes;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class MovieTimeDateResource extends JsonResource
{
    public function toArray($request)
    {
        $dateStart = Carbon::parse($this['date_start'])->locale('es');

        $nameDay = $dateStart->isToday() ? 'HOY' : ucfirst($dateStart->dayName);

        return [
            'name_day'     => $nameDay,                  
            'number_day'   => $dateStart->format('d'),    
            'name_month'   => ucfirst($dateStart->monthName),
            'date'         => $this['date_start']
        ];
    }
}