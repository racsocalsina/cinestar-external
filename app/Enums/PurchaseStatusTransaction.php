<?php


namespace App\Enums;


class PurchaseStatusTransaction
{
    public const PAYMENT_IN_PROCESS = 'payment-in-process';
    public const PAYMENT_CONFIRMED = 'payment-confirmed';
    //public const SENT_TO_HEADQUARTERS = 'sent-to-headquarters';
    public const TICKET_SENT = 'ticket-sent';
    //public const PURCHASE_COMPLETED = 'purchase-completed';

    private const PAYMENT_IN_PROCESS_NAME = 'Pago en proceso';
    private const PAYMENT_CONFIRMED_NAME = 'Pago confirmado';
    //private const SENT_TO_HEADQUARTERS_NAME = 'Enviado a sede';
    private const TICKET_SENT_NAME = 'Boleto enviado';
    //private const PURCHASE_COMPLETED_NAME = 'Compra completada';


    public static function getStatusName($status)
    {
        switch ($status) {
            case self::PAYMENT_IN_PROCESS:
                return self::PAYMENT_IN_PROCESS_NAME;
            case self::PAYMENT_CONFIRMED:
                return self::PAYMENT_CONFIRMED_NAME;
           /* case self::SENT_TO_HEADQUARTERS:
                return self::SENT_TO_HEADQUARTERS_NAME;*/
            case self::TICKET_SENT:
                return self::TICKET_SENT_NAME;
           /* case self::PURCHASE_COMPLETED:
                return self::PURCHASE_COMPLETED_NAME;*/
            default:
                return $status;
        }
    }

    public static function getAllForBO(): array
    {
        return [
            ['id' => self::PAYMENT_IN_PROCESS, 'name' => self::PAYMENT_IN_PROCESS_NAME],
            ['id' => self::PAYMENT_CONFIRMED, 'name' => self::PAYMENT_CONFIRMED_NAME],
            //['id' => self::SENT_TO_HEADQUARTERS, 'name' => self::SENT_TO_HEADQUARTERS_NAME],
            ['id' => self::TICKET_SENT, 'name' => self::TICKET_SENT_NAME],
            //['id' => self::PURCHASE_COMPLETED, 'name' => self::PURCHASE_COMPLETED_NAME],
        ];
    }
}
