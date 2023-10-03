<?php


namespace App\Services\PayU\Shared\Enums;


class PaymentMethods
{
    public const MASTERCARD = 'MASTERCARD';
    public const VISA = 'VISA';
    public const AMEX = 'AMEX';
    public const DINERS = 'DINERS';

    public const ALL_VALUES = [
        self::MASTERCARD,
        self::VISA,
        self::AMEX,
        self::DINERS,
    ];

}
