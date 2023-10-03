<?php


namespace App\Models\Rooms\Repositories\Interfaces;


interface RoomRepositoryInterface
{
    function sync($body, $syncHeadquarter = null);
}
