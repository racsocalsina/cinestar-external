<?php


namespace App\Enums;


class InternalValidationResponseType
{
    public const SUCCESS = 'SUCCESS';
    public const ERROR = 'ERROR';
    public const SEATS_NOT_MATCH = 'SEATS_NOT_MATCH';
    public const SEATS_ALREADY_OCCUPIED = 'SEATS_ALREADY_OCCUPIED';
}