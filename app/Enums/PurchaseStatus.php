<?php


namespace App\Enums;


class PurchaseStatus
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const ERROR = 'error';
    public const ERROR_PAYMENT_GATEWAY = 'error-pg';
    public const ERROR_BILLING = 'error-billing';
    public const ERROR_INTERNAL = 'error-internal';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    public const ERROR_SEND_EMAIL = 'error-send-email';

    private const PENDING_NAME = 'Pendiente';
    private const CONFIRMED_NAME = 'Confirmado';
    private const ERROR_NAME = 'Error';
    private const ERROR_PAYMENT_GATEWAY_NAME = 'Error Pasarela Pago';
    private const ERROR_BILLING_NAME = 'Error Facturador';
    private const ERROR_INTERNAL_NAME = 'Error ERP';
    private const COMPLETED_NAME = 'Completado';
    private const CANCELLED_NAME = 'Anulado';
    public const ERROR_SEND_EMAIL_NAME = 'Error envio Email';

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::PENDING:
                return self::PENDING_NAME;
            case self::CONFIRMED:
                return self::CONFIRMED_NAME;
            case self::ERROR:
                return self::ERROR_NAME;
            case self::ERROR_PAYMENT_GATEWAY:
                return self::ERROR_PAYMENT_GATEWAY_NAME;
            case self::ERROR_BILLING:
                return self::ERROR_BILLING_NAME;
            case self::ERROR_INTERNAL:
                return self::ERROR_INTERNAL_NAME;
            case self::COMPLETED:
                return self::COMPLETED_NAME;
            case self::CANCELLED:
                return self::CANCELLED_NAME;
            default:
                return $status;
        }
    }

    public static function getAllForBO(): array
    {
        return [
            ['id' => self::CONFIRMED, 'name' => self::CONFIRMED_NAME],
            ['id' => self::ERROR_BILLING, 'name' => self::ERROR_BILLING_NAME],
            ['id' => self::ERROR_INTERNAL, 'name' => self::ERROR_INTERNAL_NAME],
            ['id' => self::COMPLETED, 'name' => self::COMPLETED_NAME],
            ['id' => self::CANCELLED, 'name' => self::CANCELLED_NAME],
        ];
    }
}
