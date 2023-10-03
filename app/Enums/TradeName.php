<?php


namespace App\Enums;


class TradeName
{
    public const MOVIETIME = 'MOVIETIME';
    public const CINESTAR = 'CINESTAR';

    public const ALL_VALUES = [
        self::CINESTAR,
        self::MOVIETIME,
    ];

    public const ALL_DATA = [
        ['id' => self::CINESTAR, 'name' => 'Cinestar'],
        ['id' => self::MOVIETIME, 'name' => 'Movie Time'],
    ];

    public static function getNameByTrade($tradeName)
    {
        $index = array_search($tradeName, array_column(self::ALL_DATA, 'id'));
        return self::ALL_DATA[$index]['name'];
    }
}
