<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\ProductPrices\Repositories\Interfaces\ProductPriceRepositoryInterface;

class ProductPriceImport
{
    private $headquarter;
    private ProductPriceRepositoryInterface $productPriceRepository;

    public function __construct(ProductPriceRepositoryInterface $productPriceRepository)
    {
        $this->productPriceRepository = $productPriceRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/product-price";
        $response = HelperImport::getResponseFromInternalByService($url, $token, ['point_sale' => $headquarter->prepto]);
        $this->insert($response['data']);

        $meta = $response['meta'];
        if ($meta['last_page'] < 2)
            return;

        for ($i = 2; $i <= $meta['last_page']; $i++) {
            $response = HelperImport::getResponseFromInternalByService($url, $token, [
                'page' => $i,
                'point_sale' => $headquarter->prepto,
            ]);
            $this->insert($response['data']);
        }
    }

    private function insert($data)
    {
        foreach ($data as  $value) {
            $body = HelperImport::buildBody($this->headquarter->api_url, $value);
            $this->productPriceRepository->sync($body, $this->headquarter);
        }
    }
}
