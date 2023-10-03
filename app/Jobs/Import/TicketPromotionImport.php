<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;

class TicketPromotionImport
{
    private $headquarter;
    private TicketPromotionRepositoryInterface $ticketPromotionRepository;

    public function __construct(TicketPromotionRepositoryInterface $ticketPromotionRepository)
    {
        $this->ticketPromotionRepository = $ticketPromotionRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/ticket-promotions";
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
            $this->ticketPromotionRepository->sync($body, $this->headquarter);
        }
    }
}
