<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;

class SettingImport
{
    private $headquarter;
    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/settings";
        $response = HelperImport::getResponseFromInternalByService($url, $token, []);
        $this->insert($response);
    }

    private function insert($data){
        $body = HelperImport::buildBody($this->headquarter->api_url, $data);
        $this->settingRepository->sync($body);
    }
}
