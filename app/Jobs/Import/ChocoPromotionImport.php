<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\ChocoPromotions\Repositories\Interfaces\ChocoPromotionRepositoryInterface;

class ChocoPromotionImport
{
    private $headquarter;
    private ChocoPromotionRepositoryInterface $chocoPromotionRepository;

    public function __construct(ChocoPromotionRepositoryInterface $chocoPromotionRepository)
    {
        $this->chocoPromotionRepository = $chocoPromotionRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/choco-promotions";
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
            $this->chocoPromotionRepository->sync($body, $this->headquarter);
        }
    }
}
