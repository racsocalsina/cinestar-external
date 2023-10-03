<?php


namespace App\Models\Claim\Repositories\Interfaces;


use App\Models\Claim\Claim;

interface ClaimRepositoryInterface
{
    public function create($body);
    public function buildPDFClaimDocument(Claim $model);
}
