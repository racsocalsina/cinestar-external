<?php


namespace App\Models\Cards\Repositories\Interfaces;


use App\Models\Cards\Card;

interface CardRepositoryInterface
{
    public function all(int $userId);
    public function queryable();
    public function create(array $data);
    public function delete(Card $model);
    public function createTokenization(array $data);
    public function deleteTokenization(Card $card) : void;
}
