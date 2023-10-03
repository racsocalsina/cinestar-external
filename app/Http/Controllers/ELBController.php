<?php


namespace App\Http\Controllers;


class ELBController extends Controller
{
    public function healthCheck()
    {
        return ['success' => 200];
    }

    public function elbTest()
    {
        try {
            $url = 'http://169.254.169.254/openstack/latest/meta_data.json';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => 0
            ));

            $response = curl_exec($curl);

            if (!curl_errno($curl)) {
                $info = curl_getinfo($curl);
                if ($info['http_code'] == 200) {
                    $data = json_decode($response);
                    return ['uuid' => $data->uuid, 'name' => $data->name] ;
                }

            }
            curl_close($curl);
            return ['data' => null];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
