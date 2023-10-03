<?php


namespace App\Helpers;


use App\Enums\BannerType;
use App\Enums\ElectronicBilling;
use App\Enums\GlobalEnum;
use App\Enums\SalesType;
use App\Enums\TradeName;
use App\Jobs\SendErrorEmail;
use App\Models\Settings\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FunctionHelper
{
    public static function createGuid($length = 20)
    {
        $timestamp = (str_replace('=', '', base64_encode(str_shuffle(Carbon::now()->timestamp))));
        return Str::random($length) . $timestamp;
    }

    public static function IsNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }

    public static function Trim($value)
    {
        return ltrim(rtrim($value));
    }

    public static function getSystemSupportEmailsArray()
    {
        return explode(',', str_replace(' ', '', FunctionHelper::getValueSystemConfigurationByKey('system_support_emails')));
    }

    public static function checkIfProductSyncIsCombo($artlin)
    {
        return substr(trim($artlin), 0, 2) == '01';
    }

    public static function getClientIpEnv()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    public static function getMonthNameByNumber($number): string
    {
        switch ($number) {
            case 1:
                return 'Enero';
            case 2:
                return 'Febrero';
            case 3:
                return 'Marzo';
            case 4:
                return 'Abril';
            case 5:
                return 'Mayo';
            case 6:
                return 'Junio';
            case 7:
                return 'Julio';
            case 8:
                return 'Agosto';
            case 9:
                return 'Setiembre';
            case 10:
                return 'Octubre';
            case 11:
                return 'Noviembre';
            case 12:
                return 'Diciembre';
            default:
                return "";
        }
    }

    public static function checkRangeDatesIsValidWithCurrentDate($startDate, $endDate)
    {
        return Carbon::now()->between($startDate, $endDate);
    }

    public static function getSupportEmailByTradeName($trade_name)
    {
        if ($trade_name == TradeName::CINESTAR)
            $emails = explode(',', str_replace(' ', '', FunctionHelper::getValueSystemConfigurationByKey('cinestar_support_email')));
        else
            $emails = explode(',', str_replace(' ', '', FunctionHelper::getValueSystemConfigurationByKey('movietime_support_email')));

        return $emails;
    }

    public static function getWorkWithUsEmailByTradeName($trade_name)
    {
        if ($trade_name == TradeName::CINESTAR)
            $emails = explode(',', str_replace(' ', '', FunctionHelper::getValueSystemConfigurationByKey('cinestar_work_with_us_email')));
        else
            $emails = explode(',', str_replace(' ', '', FunctionHelper::getValueSystemConfigurationByKey('movietime_work_with_us_email')));

        return $emails;
    }

    public static function getShopCodeBySalesType($salesType)
    {
        return $salesType == SalesType::TICKET ?
            ElectronicBilling::SHOP_CODE_TICKET :
            ElectronicBilling::SHOP_CODE_SWEET;
    }

    public static function generateSocCod($tradeName, $documentNumber): string
    {
        $trade = $tradeName == TradeName::CINESTAR ? 1 : 2;
        return $trade . $documentNumber;
    }

    /**
     * Get current api user by token
     */
    public static function getApiUser()
    {
        return Auth::guard('api')->user();
    }

    public static function getBannerNameByType($type)
    {
        if ($type == BannerType::MOVIL)
            return "Movíl";
        else if ($type == BannerType::WEB)
            return "Web";
        else if ($type == BannerType::RESPONSIVE)
            return "Web responsive";
        else
            return null;
    }

    public static function removeWhiteSpaces($value)
    {
        return preg_replace('/\s+/', '', $value);
    }

    public static function getValueSystemConfigurationByKey($key, $showAll = false)
    {
        $data = Setting::where('code_key', GlobalEnum::SETTINGS_CK_SYSTEM_CONFIGURATION)
            ->get(['config'])
            ->first();

        $data = $data ? $data->config : null;

        if (!$data)
            return null;

        if ($showAll)
            return $data;
        else
            if (isset($data[$key]))
                return $data[$key];

        return null;
    }

    /*
     * This function returned only the key of array
     */
    public static function searchArrayForKey($array, $keyName, $value)
    {
        foreach ($array as $key => $val) {
            if ($val[$keyName] === $value) {
                return $key;
            }
        }
        return null;
    }

    public static function sendErrorMail($exceptionDto)
    {
        if (config('app.error_mail')) {
            SendErrorEmail::dispatch($exceptionDto);
        }

    }
}
