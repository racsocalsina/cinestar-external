<?php


namespace App\Models\Movies\Repositories\Interfaces;


interface MovieValidPromotionRepositoryInterface
{
    public function checkMovieIsValidForPromotions($movieTimeId) : bool;
}
