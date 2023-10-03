<?php


namespace App\Enums;


class ActionType
{
    public const CREATE = 'CREATE';
    public const DELETE = 'DELETE';
    public const UPDATE = 'UPDATE';

    public const ALL_VALUES = [
        self::CREATE,
        self::DELETE,
        self::UPDATE,
    ];
}
