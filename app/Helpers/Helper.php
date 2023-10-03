<?php

namespace App\Helpers;

use App\Enums\GlobalEnum;
use App\Enums\SeatType;
use App\Enums\TradeName;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Validator;

class Helper
{
    /**
     * Pasar un string fornado por group cant a json
     *
     * @param String $data
     * @return Array
     */
    public static function transformDataGroupConcat($data, $divider = ';')
    {
        return json_encode(explode($divider, $data));
    }
    /**
     * Agregar meses a una fecha en formato Y-m-d
     *
     * @param string $date
     * @param integer $dayDefault
     * @param integer $cantMonth
     * @return date
     */
    public static function addMonthForDate($date, $dayDefault = 1, $typeFormat = 1, $cantMonth = 1)
    {
        if ($typeFormat === 1) {
            $dateConversion = Date::createFromFormat('d', $date);
        } else {
            $dateConversion = Carbon::parse($date);
        }
        $year = $dateConversion->year;
        $month = intval($dateConversion->month);
        if ($cantMonth > 0) {
            $month += $cantMonth;
        }
        while ($month > 12) {
            $year += 1;
            $month = $month - 12;
        }
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
        $dayDefault = str_pad($dayDefault, 2, "0", STR_PAD_LEFT);
        $temp_date = $year . '-' . $month . '-' . $dayDefault;
        $data = Validator::make(['date' => $temp_date], [
            'date' => 'date'
        ]);
        if (!$data->passes()) {
            $dateResponse = Date::parse($year . '-' . $month . '-01')->endOfMonth()->startOfDay();
        } else {
            $dateResponse = Date::parse($temp_date);
        }

        return $dateResponse;
    }
    /**
     * Restar meses a una fecha en formato Y-m-d
     *
     * @param string $date
     * @param integer $dayDefault
     * @param integer $cantMonth
     * @return date
     */
    public static function subtractMonthForDate($date, $dayDefault = 1, $typeFormat = 1, $cantMonth = 1)
    {
        if ($typeFormat === 1) {
            $dateConversion = Date::createFromFormat('d', $date);
        } else if ($typeFormat === 2) {
            $dateConversion = Carbon::parse($date);
        } else if ($typeFormat === 3) {
            $dateConversion = $date;
        }
        $year = $dateConversion->year;
        $month = intval($dateConversion->month);
        if ($cantMonth > 0) {
            $month -= $cantMonth;
        }
        while ($month <= 0) {
            $year -= 1;
            $month = $month + 12;
        }
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
        $dayDefault = str_pad($dayDefault, 2, "0", STR_PAD_LEFT);
        $temp_date = $year . '-' . $month . '-' . $dayDefault;
        $data = Validator::make(['date' => $temp_date], [
            'date' => 'date'
        ]);
        if (!$data->passes()) {
            $dateResponse = Date::parse($year . '-' . $month . '-01')->endOfMonth()->startOfDay();
        } else {
            $dateResponse = Date::parse($temp_date);
        }

        return $dateResponse;
    }

    /**
     * Transformar la informacion de un que develve un Select a un Array
     *
     * @param array $data
     * @return array
     */
    public static function transformSelectToArray($data): array
    {
        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);
        return $data;
    }

    public static function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    /**
     * Validar si la fecha ingresada se encuentre dentro de los rangos establecidos
     *
     * @param date $date_inicio
     * @param date $date_fin
     * @param date $date_nueva
     * @return bool
     */
    public static function verifica_rango($date_inicio, $date_fin, $date_nueva): bool
    {
        $date_inicio = strtotime($date_inicio->format('Y-m'));
        $date_fin    = strtotime($date_fin->format('Y-m'));
        $date_nueva  = strtotime($date_nueva->format('Y-m'));
        if (($date_nueva >= $date_inicio) && ($date_nueva <= $date_fin))
            return true;
        return false;
    }
    /**
     * Reemplaza la ultima aparicion de una cadena en dentro de una cadena
     *
     * @param string $buscar
     * @param string $remplazar
     * @param string $texto
     *
     * @return string
     */
    public static function reemplazarUltimoString($buscar, $remplazar, $texto): string
    {
        $pos = strrpos($texto, $buscar);
        if ($pos !== false) {
            $texto = substr_replace($texto, $remplazar, $pos, strlen($buscar));
        }
        return $texto;
    }
    /**
     * Descompone un array y al ultimo elelemento le pone una y para concatenarlo en un string
     *
     * @param array $buscar
     *
     * @return string
     */
    public static function implodeArrayFinalY($list): string
    {
        $arrayNum = count($list);
        $i = -1;
        $texto = '';

        while (++$i < $arrayNum){
            if(strlen($texto)){
                if($i == $arrayNum - 1) {
                    $texto .=  ' y ' . $list[$i];
                }else {
                    $texto .=  ', ' . $list[$i];
                }
            }else {
                $texto .=  $list[$i];
            }
        }
        return $texto;
    }

    /**
     * Validar el numero de documento segun el tipo de documento seleccionado
     *
     * @param string $document_number
     * @param string $document_type
     *
     * @return boolean
     */
    public static function validateDocumentForType(string $document_number, string $document_type): bool {
        if($document_type === config('constants.type_document_dni')){
            if(strlen($document_number) !== 8){
                return false;
            }
            if(!is_numeric($document_number)){
                return false;
            }
        }
        if($document_type === config('constants.type_document_cde')){
            if(strlen($document_number) > 12){
                return false;
            }
            if(!ctype_alnum($document_number)){
                return false;
            }
        }
        if($document_type === config('constants.type_document_rudc')){
            if(strlen($document_number) !== 11){
                return false;
            }
            if(!is_numeric($document_number)){
                return false;
            }
            $resultado = substr($document_number, 0, 2);
            if($resultado !== '10' && $resultado !== '20'){
                return false;
            }
        }
        if($document_type === config('constants.type_document_pasaporte')){
            if(strlen($document_number) > 12){
                return false;
            }
            if(!ctype_alnum($document_number)){
                return false;
            }
        }
        if($document_type === config('constants.type_document_pdni')){
            if(strlen($document_number) > 15){
                return false;
            }
            if(!ctype_alnum($document_number)){
                return false;
            }
        }
        if($document_type === config('constants.type_document_otros')){
            if(strlen($document_number) > 15){
                return false;
            }
            if(!ctype_alnum($document_number)){
                return false;
            }
        }
        return true;
    }

    /**
     * Validar fecha segun el formato
     *
     * @param any $date
     * @param string $format
     *
     * @return void
     */
     public static function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Validacion de contraseña
     *
     * @param string $password
     *
     * @return array
     */
    public static function validatePassword(string $password): array{
        $error_clave = "";
        if (!preg_match('`[A-Z]`', $password)){
            $error_clave = "La contraseña debe tener al menos una letra mayúscula";
            return [false, $error_clave];
        }
        if (!preg_match('`[0-9]`', $password)){
            $error_clave = "La contraseña debe tener al menos un caracter numérico";
            return [false, $error_clave];
        }
        return [true, $error_clave];
    }

    public static function perPage(array $options = []) {
        $perPageByDefault = config('constants.api.per_page');

        if(array_key_exists('per_page', $options))
        {
            if(!is_numeric($options['per_page']))
                return $perPageByDefault;

            if(intval($options['per_page']) <= $perPageByDefault)
                return intval($options['per_page']);

        }
        return $perPageByDefault;
    }

    public static function getDateTimeFormat($carbonValue, $format = 'Y-m-d H:i:s')
    {
        if(is_null($carbonValue))
            return null;

        return $carbonValue->format($format);
    }

    public static function getDateFormat($carbonValue)
    {
        if(is_null($carbonValue))
            return null;

        return $carbonValue->format('Y-m-d');
    }

    public static function getTradeNameHeader()
    {
        $tradeName = trim(strtoupper(request()->headers->get('Trade-Name')));

        if($tradeName == TradeName::CINESTAR)
            return TradeName::CINESTAR;
        else if($tradeName == TradeName::MOVIETIME)
            return TradeName::MOVIETIME;

        return null;
    }

    public static function tradeNameExist($tradeName)
    {
        return in_array($tradeName, TradeName::ALL_VALUES);
    }

    public static function addSlashToUrl($url)
    {
        $last = substr($url, -1);
        if($last != "/")
            return "{$url}/";

        return $url;
    }

    public static function getFriendlyDateFormat($date, $fromFormat = 'Y-m-d H:i:s')
    {
        if(is_null($date))
            return null;

        $date = Date::createFromFormat($fromFormat, $date);

        if(Date::now()->format('Y-m-d') == $date->format('Y-m-d')){
            $value = 'Hoy, '.$date->format('j \d\e F \d\e Y');
        }else if(Date::now()->addDay(1)->format('Y-m-d') == $date->format('Y-m-d')){
            $value = 'Mañana, '.$date->format('j \d\e F \d\e Y');
        }else{
            $value = ucfirst($date->format('l ')) . $date->format('j \d\e F \d\e Y');
        }

        return $value;
    }
    //Aqui se hace el loguin con el servidor de Internal para el consumo de API
    public static function loginInternal($headquarter){
        $client = new Client();
        $api_url = Helper::addSlashToUrl($headquarter->api_url);
        $URI = "{$api_url}api/v1/consumer/auth/login";
        $myBody['username'] = $headquarter->user;
        $myBody['password'] = decrypt($headquarter->password);
        $params['form_params'] = $myBody;
        $params['timeout'] = 30;
        $response = $client->post($URI, $params);
        $body = (string) $response->getBody();
        $body = json_decode($body, true);
        return $body['data']['token'];
    }

    public static function getGraphParams($movie_time, $index) {
        $total_columns = $movie_time->room->total_columns;
        $row_num = ceil(($index + 1)/ $total_columns);
        $actual_row = explode("/", $movie_time->planner_graph)[$row_num - 1];
        $index_on_row = $total_columns - (($total_columns * $row_num) - $index);
        $partial_row = substr($actual_row, 0, $index_on_row + 1);
        $total_hall = substr_count($partial_row, SeatType::HALL);
        $graph_index = $index + $row_num - 1;
        $seat_status = substr($partial_row, -1);
        return [$seat_status, $total_hall, $graph_index, $row_num];
    }

    public static function getGraphIndex($movie_time, $index) {
        $total_columns = $movie_time->room->total_columns;
        $row_num = ceil(($index + 1) / $total_columns);
        return $index + $row_num - 1;
    }

    public static function reserveSeatInternal($purchase, $ticket){
        $token = Helper::loginInternal($purchase->headquarter);
        $client = new Client();
        $api_url = Helper::addSlashToUrl($purchase->headquarter->api_url);
        $URI = "{$api_url}api/v1/consumer/reserves";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $params['json'] = [
            'funkey' => $purchase->movie_time->remote_funkey,
            'row' => $ticket['chair_row'],
            'column' => $ticket['chair_column'],
            'sales_point_key' => $purchase->headquarter->point_sale,
            'purchase_id' => $purchase->id
        ];
        $params['headers'] = $headers;
        $response = $client->post($URI, $params);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    public static function deleteSeatInternal($ticket){
        $purchase = $ticket->purchase;
        $token = Helper::loginInternal($purchase->headquarter);
        $client = new Client();
        $api_url = Helper::addSlashToUrl($purchase->headquarter->api_url);
        $URI = "{$api_url}api/v1/consumer/reserves";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $params['json'] = [
            'uuid' => $ticket->uuid
        ];
        $params['headers'] = $headers;
        $response = $client->delete($URI, $params);
        $body = (string)$response->getBody();
        return json_decode($body, true);
    }

    public static function checkSeatAvailability($purchase, $seat)
    {
        $client = new Client();
        $api_url = Helper::addSlashToUrl($purchase->headquarter->api_url);
        $url = "{$api_url}api/v1/consumer/check-availability";
        $headers = [
            'Accept' => 'application/json',
        ];
        $response = $client->get($url, [
            'headers' => $headers,
            'query'   => [
                'funkey' => $purchase->movie_time->remote_funkey,
                'seat'   => $seat
            ]
        ]);
        $body = (string)$response->getBody();
        return json_decode($body, true);
    }

    public static function checkSeatsAvailabilityByPurchase($purchase, $seats){
        $token = Helper::loginInternal($purchase->headquarter);
        $client = new Client();
        $api_url = Helper::addSlashToUrl($purchase->headquarter->api_url);
        $URI = "{$api_url}api/v1/consumer/check-availability-by-purchase";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $params['json'] = [
            'funkey' => $purchase->movie_time->remote_funkey,
            'seats' => $seats,
            'purchase_id' => $purchase->id,
        ];
        $params['headers'] = $headers;
        $response = $client->post($URI, $params);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    public static function dateTimeByFormat($value, $fromFormat = null, $toFormat = null)
    {

        $return = null;

        if ($value) {

            // values by default
            $default_format = 'Y-m-d H:i:s';

            // formats by default or custom
            $fromFormat = ($fromFormat == null ? $default_format : $fromFormat);
            $toFormat = ($toFormat == null ? $default_format : $toFormat);

            // create datetime from format
            $date = \DateTime::createFromFormat($fromFormat, $value);

            // set formart to return
            $return = $date->format($toFormat);

        }

        return $return;
    }

    public static function getImageSweetPathByType($image)
    {
        if(!is_null($image))
            return config('constants.path_images').env('BUCKET_ENV').GlobalEnum::PRODUCTS_FOLDER."/".$image;

        return asset('assets/img/no-product.png');
    }
}
