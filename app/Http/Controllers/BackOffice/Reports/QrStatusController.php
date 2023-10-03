<?php


namespace App\Http\Controllers\BackOffice\Reports;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Reports\QrStatusReportRequest;
use App\Services\Reports\QrStatus\Actions\QrStatusReport;
use App\Traits\ApiResponser;

class QrStatusController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('permission:read-reports');
    }

    public function index(QrStatusReportRequest $request, QrStatusReport $action)
    {
        return $action->execute($request->code);
    }
}