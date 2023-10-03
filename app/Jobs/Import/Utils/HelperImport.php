<?php


namespace App\Jobs\Import\Utils;


use App\Enums\GlobalEnum;
use GuzzleHttp\Client;

class HelperImport
{
    public static function buildBody($apiUrl, $data)
    {
        return [
            'action' => GlobalEnum::ACTION_SYNC_IMPORT,
            'url'    => $apiUrl,
            'data'   => $data
        ];
    }

    public static function getResponseFromInternalByService(string $url, string $token, array $queryParams)
    {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $response = $client->get($url, [
            'headers' => $headers,
            'query' => $queryParams
        ]);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

}
