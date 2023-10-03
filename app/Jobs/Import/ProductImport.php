<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\Products\Repositories\Interfaces\ProductRepositoryInterface;

class ProductImport
{
    private $headquarter;
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/products";
        $response = HelperImport::getResponseFromInternalByService($url, $token, ['prepto' => $headquarter->prepto]);
        $this->insert($response['data']);

        $meta = $response['meta'];
        if ($meta['last_page'] < 2)
            return;

        for ($i = 2; $i <= $meta['last_page']; $i++) {
            $response = HelperImport::getResponseFromInternalByService($url, $token, [
                'page' => $i,
                'prepto' => $headquarter->prepto,
            ]);
            $this->insert($response['data']);
        }
    }

    private function insert($data)
    {
        foreach ($data as  $value) {
            $body = HelperImport::buildBody($this->headquarter->api_url, $value);
            $this->productRepository->sync($body);
        }
    }
}
