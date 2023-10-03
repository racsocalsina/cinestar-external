<?php


namespace App\Enums;


class BusinessName
{
    public const STAR_PLAZA = 'STAR_PLAZA';
    public const TOP_RANK = 'TOP_RANK';

    public const ALL_VALUES = [
        self::STAR_PLAZA,
        self::TOP_RANK
    ];

    public const ALL_DATA = [
        [
            'id'    => self::TOP_RANK,
            'name'  => 'Top Rank',
            'value' => 1
        ],
        [
            'id'    => self::STAR_PLAZA,
            'name'  => 'Star Plaza',
            'value' => 'f'
        ],
    ];

    public static function getValueByBusinessName($BusinessName)
    {
        $index = array_search($BusinessName, array_column(self::ALL_DATA, 'id'));
        return self::ALL_DATA[$index]['value'];
    }

    public static function getNameByBusinessName($BusinessName)
    {
        $index = array_search($BusinessName, array_column(self::ALL_DATA, 'id'));
        return self::ALL_DATA[$index]['name'];
    }
}
