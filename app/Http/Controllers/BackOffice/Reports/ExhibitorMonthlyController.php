<?php


namespace App\Http\Controllers\BackOffice\Reports;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Reports\ExhibitorMonthlyRequest;
use App\Services\Reports\ExhibitorMonthly\Actions\ExhibitorMonthly;
use App\Services\Reports\ExhibitorMonthly\Dtos\ExhibitorMonthlyDto;
use App\Traits\ApiResponser;

class ExhibitorMonthlyController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('permission:read-reports');
    }

    public function index(ExhibitorMonthlyRequest $request, ExhibitorMonthly $action)
    {
        $params = new ExhibitorMonthlyDto();
        $params->setYear(explode("-", $request->date)[0]);
        $params->setMonth(explode("-", $request->date)[1]);
        $params->setTradeName($request->trade_name);

        return $this->success($action->execute($params));
    }
}