<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\Bins\Repositories\Interfaces\BinRepositoryInterface;

class BinImport
{
    private $headquarter;
    private BinRepositoryInterface $binRepository;

    public function __construct(BinRepositoryInterface $binRepository)
    {
        $this->binRepository = $binRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/bins";
        $response = HelperImport::getResponseFromInternalByService($url, $token, ['point_sale' => $this->headquarter->point_sale]);
        $this->insert($response['data']);

        $meta = $response['meta'];
        if ($meta['last_page'] < 2)
            return;

        for ($i = 2; $i <= $meta['last_page']; $i++) {
            $response = HelperImport::getResponseFromInternalByService($url, $token, ['page' => $i, 'point_sale' => $this->headquarter->point_sale]);
            $this->insert($response['data']);
        }
    }

    private function insert($data)
    {
        foreach ($data as $value) {
            $body = HelperImport::buildBody($this->headquarter->api_url, $value);
            $this->binRepository->sync($body, $this->headquarter);
        }
    }
}