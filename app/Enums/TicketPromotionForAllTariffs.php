<?php


namespace App\Enums;


class TicketPromotionForAllTariffs
{
    public const CODE_FROM = 401;
    public const CODE_TO = 409;

    public static function codes()
    {
        return [
            self::CODE_FROM, self::CODE_TO
        ];
    }
}
