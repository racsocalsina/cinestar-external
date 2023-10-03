<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\ChocoAwards\Repositories\Interfaces\ChocoAwardRepositoryInterface;

class ChocoAwardImport
{
    private $headquarter;
    private ChocoAwardRepositoryInterface $chocoAwardRepository;

    public function __construct(ChocoAwardRepositoryInterface $chocoAwardRepository)
    {
        $this->chocoAwardRepository = $chocoAwardRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/choco-awards";
        $response = HelperImport::getResponseFromInternalByService($url, $token, []);
        $this->insert($response['data']);

        $meta = $response['meta'];
        if ($meta['last_page'] < 2)
            return;

        for ($i = 2; $i <= $meta['last_page']; $i++) {
            $response = HelperImport::getResponseFromInternalByService($url, $token, ['page' => $i]);
            $this->insert($response['data']);
        }
    }

    private function insert($data)
    {
        foreach ($data as $value) {
            $body = HelperImport::buildBody($this->headquarter->api_url, $value);
            $this->chocoAwardRepository->sync($body, $this->headquarter);
        }
    }
}
