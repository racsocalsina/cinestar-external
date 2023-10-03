<?php


namespace App\Enums;


class ChocoPromotionType
{
    // Promociones con descuento aplicado a toda la CHOCOLATERIA
    public const PERCENTAGE_DSCTO_TOTAL = 1;

    // Promociones con DESCUENTO especial a ciertos productos
    public const PERCENTAGE_DSCTO_SOME_PRODUCTS = 2;

    // Promociones con PRECIO especial a ciertos productos
    public const SPECIAL_PRICE_SOME_PRODUCTS = 3;

    // Las promociones GRATIS a ciertos productos
    public const FREE_SOME_PRODUCTS = 4;
}