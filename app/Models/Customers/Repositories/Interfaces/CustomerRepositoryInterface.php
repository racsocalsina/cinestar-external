<?php


namespace App\Models\Customers\Repositories\Interfaces;


interface CustomerRepositoryInterface
{
    public function search(array $params);
    public function ranking(array $params);
    public function queryable();
}
