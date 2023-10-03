<?php


namespace App\Models\AutoIncrementCodes\Service;

use App\Enums\VoucherType;
use App\Models\AutoIncrementCodes\AutoIncrementCode;

class InvoiceSequenceFactory
{
    public static function reserveNextCode(string $voucherType, string $pointSale,string $shopCode, string $business_name): array
    {
        $feSerialNumber = self::buildFeSerialNumber($voucherType, $pointSale, $shopCode);
        $internalSerialNumber = self::buildInternalSerialNumber($voucherType, $pointSale, $shopCode);

        $last = self::lastCurrentNumber($feSerialNumber, $business_name);
        $next = $last + 1;
        $documentNumber = sprintf('%06d', $next);

        AutoIncrementCode::query()->updateOrCreate(
            [
                'code'          => $feSerialNumber,
                'business_name' => $business_name,
            ],
            [
                'current' => $next
            ]
        );

        return [
            'document_number'        => $documentNumber,
            'fe_serial_number'       => $feSerialNumber,
            'internal_serial_number' => $internalSerialNumber,
            'business_name'          => $business_name
        ];
    }



    public static function buildInternalSerialNumber(string $voucherType, string $pointSale, string $shopCode)
    {
        $voucherTypeChar = $voucherType == VoucherType::CODE_TICKET ? VoucherType::CODE_TICKET : VoucherType::CODE_INVOICE;
        return $voucherTypeChar . '-' . $pointSale . $shopCode;
    }

    public static function getDocumentNumberByRemoteMovKey($remoteMovKey)
    {
        $data = explode('-', $remoteMovKey);
        return $data[2];
    }

    public static function getFeSerialNumberByRemoteMovKey($remoteMovKey)
    {
        $data = explode('-', $remoteMovKey);
        $voucherTypeChar = $data[0];
        $pointSaleAndShopCodeChar = $data[1];
        $voucherTypeChar = self::getVoucherTypeByFe($voucherTypeChar);

        return $voucherTypeChar . $pointSaleAndShopCodeChar;
    }

    private static function lastCurrentNumber(string $code, string $business_name)
    {
        $data = AutoIncrementCode::where('code', $code)
            ->where('business_name', $business_name)
            ->orderBy('current', 'desc')
            ->first();

        $current = $data ? $data->current : 0;

        if (env('FAC_ENV') === 'qa') {
            $current = ($current == 0 ? 10000 : $current);
        } else if (env('FAC_ENV') === 'test') {
            $current = ($current == 0 ? 20000 : $current);
        } else if (env('FAC_ENV') === 'local') {
            $current = ($current == 0 ? 30000 : $current);
        }

        return $current;
    }

    private static function buildFeSerialNumber(string $voucherType, string $pointSale, string $shopCode)
    {
        $voucherTypeChar = self::getVoucherTypeByFe($voucherType);
        return $voucherTypeChar . $pointSale . $shopCode;
    }

    private static function getVoucherTypeByFe($voucherType)
    {
        return $voucherType == VoucherType::CODE_TICKET ? 'B' : 'F';
    }
}
