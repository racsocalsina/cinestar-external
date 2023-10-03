<?php


namespace App\Models\Movies\Repositories;


use App\Models\Holidays\Holiday;
use App\Models\Movies\Repositories\Interfaces\MovieValidPromotionRepositoryInterface;
use App\Models\MovieTimes\MovieTime;
use Carbon\Carbon;

class MovieValidPromotionRepository implements MovieValidPromotionRepositoryInterface
{
    public function checkMovieIsValidForPromotions($movieTimeId) : bool
    {
        $movieTime = MovieTime::with(['movie'])
            ->whereId($movieTimeId)
            ->first();

        if ($this->checkIfDayIsHoliday($movieTime)) return false;
        if ($this->checkIfMovieFunctionIsMidnight($movieTime)) return false;
        if ($this->checkIfMovieIsPreview($movieTime)) return false;
        if ($this->checkIfMovieDateIsTheFirstWeekOfPremiere($movieTime)) return false;
        if ($movieTime->headquarter_id == 35) return false;

        return true;
    }

    private function checkIfDayIsHoliday($movieTime) : bool
    {
        $scheduledAt = Carbon::createFromFormat('Y-m-d', $movieTime->date_start)->format('m-d');
        $holiday = Holiday::where('scheduled_at', $scheduledAt)->first();
        return isset($holiday->id);
    }

    private function checkIfMovieFunctionIsMidnight($movieTime) : bool
    {
        $time = Carbon::createFromTimeString($movieTime->time_start);
        $start = Carbon::createFromTimeString('12:00');
        $end = Carbon::createFromTimeString('12:59');
        return $time->between($start, $end);
    }

    private function checkIfMovieIsPreview($movieTime): bool
    {
        if(!isset($movieTime->date_start))
            return false;

        $premiereDate = Carbon::createFromFormat('Y-m-d', $movieTime->movie->premier_date);
        $date = Carbon::createFromFormat('Y-m-d', $movieTime->date_start);
        return $date < $premiereDate;
    }

    private function checkIfMovieDateIsTheFirstWeekOfPremiere($movieTime): bool
    {
        if(!isset($movieTime->date_start))
            return false;

        $premiereDate = Carbon::createFromFormat('Y-m-d', $movieTime->movie->premier_date);

        $date = Carbon::createFromFormat('Y-m-d', $movieTime->date_start);
        $start = $premiereDate->copy();
        $end = $premiereDate->addDays(7);

        return $date->between($start, $end);
    }
}
