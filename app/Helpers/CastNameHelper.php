<?php


namespace App\Helpers;

use App\Enums\DocumentTypes;
use App\Enums\GlobalEnum;
use App\Enums\MDD33DocumentTypes;

class CastNameHelper
{
    public static function getConditionalName($value){

        if(!FunctionHelper::IsNullOrEmptyString($value)){
            $value = FunctionHelper::Trim($value);
            return ($value == 1 ? "Si" : "No");
        }
        return null;
    }

    public static function getEnabledName($value)
    {
        if(is_bool($value))
            return ($value ? "Activo" : "Inactivo");

        if(!FunctionHelper::IsNullOrEmptyString($value)){
            $value = FunctionHelper::Trim($value);
            return ($value == 1 ? "Activo" : "Inactivo");
        }

        return null;
    }

    public static function getSyncStatusName($value)
    {
        if($value == GlobalEnum::SYNC_LOG_STATUS_SYNCING)
            return "Sincronizando";
        else if($value == GlobalEnum::SYNC_LOG_STATUS_SUCCESS)
            return "Completado";
        else if($value == GlobalEnum::SYNC_LOG_STATUS_ERROR)
            return "Error";

        return null;
    }

    public static function getMDD33($documentTypeCode)
    {
        if($documentTypeCode == DocumentTypes::DNI)
            return MDD33DocumentTypes::DNI;
        else if($documentTypeCode == DocumentTypes::CARNET)
            return MDD33DocumentTypes::CARNET;
        else if($documentTypeCode == DocumentTypes::PASAPORTE)
            return MDD33DocumentTypes::PASAPORTE;

        return MDD33DocumentTypes::DNI;
    }

    public static function getSweetNameByQuantity($type, $quantity)
    {
        if($type == 'product')
            return "producto" . ($quantity == 1 ? '' : 's');
        else
            return "combo" . ($quantity == 1 ? '' : 's');
    }
}
