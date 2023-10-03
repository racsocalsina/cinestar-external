<?php


namespace App\Enums;


class SeatType
{
    public const FOUR_X = '4';
    public const UNAVAILABLE = 'N';
    public const AVAILABLE = 'A';
    public const RESERVED = 'R';
    public const TAKEN = 'O';
    public const HALL = 'P';
    public const WHEELCHAIR = 'S';

    public const AVAILABLE_TYPES = [
        self::FOUR_X,
        self::AVAILABLE,
        self::WHEELCHAIR
    ];

    public const UNAVAILABLE_TYPES = [
        self::UNAVAILABLE,
        self::TAKEN
    ];
}
