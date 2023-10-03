<?php


namespace App\Models\Purchases\Repositories\Interfaces;


use App\Http\Requests\Purchase\PurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Models\Purchases\Purchase;

interface PurchaseRepositoryInterface
{
    public function queryable();
    public function create(PurchaseRequest $request);
    public function update($id, UpdatePurchaseRequest $request);
    public function getGraphByUser($id);
    public function updateAsConfirmed(Purchase $purchase, $response);
    public function getPurchasePaymentData($id);
    public function destroy($id);
    public function getAllConfirmedPurchasePaymentByUser($userId);
    public function deleteItems($purchase);
    public function getSoldItemTypesOfPurchase($purchaseId);
    public function getTotalData($params);
    public function getSchedules($request);
    public function getByRemoteKey($remoteKey,$headquarter_id);
    public function purchaseIsFree($purchase);
    public function updateAsError($errorStatus, $purchase, $emailSubject, $exception);
    public function searchBO($request);
    public function transactionSearchBO($request);
    public function getByPurchase($id);
    public function updateStatusTransaction($id, array $data);
    public function transactionsPerDay();
    public function transactionsPerMonth();
    public function transactionsPerWeek();
    public function purchasesTransactionPayu($request);
}
