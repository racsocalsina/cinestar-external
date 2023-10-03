<?php


namespace App\Enums;


class TicketPromotionType
{
    // Promociones Corporativas para empresas
    public const CORPORATIVE = 1;

    // Promocion que aplica para la compra de 2 entradas donde la segunda cuesta a un precio determinado
    public const PORCENTAJE_DSCTO_POR_2DA_ENTRADA = 3;

    // Por ejemplo: Aca esta incluida la promo 2x1 (campo tickets_number)
    public const ENTRADAS_GRATIS_X_TICKETS_NUMBER = 4;

    // Promociones donde se usa la tarifa plana
    public const DESCUENTO_POR_TARIFA_PLANA = 5;

    // Promociones de cumpleañero
    public const BIRTHDAY = 6;
}