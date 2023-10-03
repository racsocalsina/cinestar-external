<?php


namespace App\Enums;


class PageWeb
{
    public const HOME = 'home';
    public const SOCIO = 'socio';
    public const COPORATIVO = 'corporativo';
    public const PROMOCION = 'promocion';

    public static function get()
    {
        return [
            self::HOME,
            self::SOCIO,
            self::COPORATIVO,
            self::PROMOCION,
        ];
    }

    public static function string()
    {
        return implode(",", self::get());
    }
}
