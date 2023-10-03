<?php


namespace App\Enums;


class BannerType
{
    public const MOVIL = 'movil';
    public const WEB = 'web';
    public const RESPONSIVE = 'responsive';

    public const ALL_VALUES = [
        self::MOVIL,
        self::WEB,
        self::RESPONSIVE,
    ];
}