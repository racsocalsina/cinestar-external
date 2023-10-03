<?php


namespace App\Models\Admins\Repositories\Interfaces;


use App\Models\Admins\Admin;

interface AdminRepositoryInterface
{
    public function queryable();
    public function search(array $params);
    public function get(int $id);
    public function create(array $data);
    public function update(Admin $model, array $data);
    public function delete(Admin $model);
    public function getByDocumentNumber(string $documentNumber, bool $dataRelated);
}
