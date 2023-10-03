<?php


namespace App\Http\Controllers\API\Share;


use App\Http\Controllers\ApiController;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class SettingsController extends ApiController
{
    private $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function billboardDates()
    {
        $result = $this->settingRepository->billboardDates();
        $start_day = $result['config']['start_day'];
        $end_day = $result['config']['end_day'];
        $dayOfWeek = (Carbon::now('America/Lima')->dayOfWeek) + 1;
        if($dayOfWeek < $start_day){
            $diffDays = $start_day - $dayOfWeek;
        }else{
            $diffDays = (7 - $dayOfWeek) + $end_day;
        }
        $arrayDays = $this->buildDayList($diffDays);
        return $this->successResponse($arrayDays);
    }

    public function billboardDatesNextReleases(Request $request)
    {
        $result = $this->settingRepository->billboardDatesNextReleases($request->movie_id);
        $arrayDays = [];
        foreach ($result as $value) {
            $day = Date::createFromFormat('Y-m-d', $value->date_start);
            $name = ucfirst($day->format('l')).', '.$day->format('d M');
            $arrayDays[] = [
                'name' => $name,
                'date' => $value->date_start
            ];
        }
        return $this->successResponse($arrayDays);
    }

    private function buildDayList($diffDays): array
    {
        $arrayDays = [];
        for ($i=0; $i <= $diffDays; $i++) {
            $day = Date::now('America/Lima')->addDay($i);
            $name = '';
            if(Date::now('America/Lima')->format('Y-m-d') == $day->format('Y-m-d')){
                $name = 'Hoy, '.$day->format('d M');
            }else if(Date::now('America/Lima')->addDay(1)->format('Y-m-d') == $day->format('Y-m-d')){
                $name = 'Mañana, '.$day->format('d M');
            }else{
                $name = ucfirst($day->format('l')).', '.$day->format('d M');
            }
            $arrayDays[] = [
                'name' => $name,
                'date' => $day->format('Y-m-d')
            ];
        }
        return $arrayDays;
    }
}
