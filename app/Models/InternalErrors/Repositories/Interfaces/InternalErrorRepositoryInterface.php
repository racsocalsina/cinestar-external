<?php


namespace App\Models\InternalErrors\Repositories\Interfaces;


interface InternalErrorRepositoryInterface
{
    public function create(array $body);
    public function searchBO($request);
}
