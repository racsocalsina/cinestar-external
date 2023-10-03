<?php


namespace App\Http\Controllers\BackOffice\Purchases;


use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Purchase\PurchaseCancelledUpdateRequest;
use App\Models\Purchases\Purchase;
use App\Traits\ApiResponser;

class PurchaseCancelledProcessController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('permission:read-reports');
    }

    public function __invoke(PurchaseCancelledUpdateRequest $request, Purchase $purchase)
    {
        $purchase->status = PurchaseStatus::CANCELLED;
        $purchase->save();

        return $this->success();
    }
}