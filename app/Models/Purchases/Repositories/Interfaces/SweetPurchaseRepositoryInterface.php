<?php


namespace App\Models\Purchases\Repositories\Interfaces;


use App\Http\Requests\Purchase\SweetPurchaseRequest;
use App\Http\Requests\Purchase\UpdateSweetPurchaseRequest;

interface SweetPurchaseRepositoryInterface
{
    public function create(SweetPurchaseRequest $request);
    public function update($id, UpdateSweetPurchaseRequest $request);
}
