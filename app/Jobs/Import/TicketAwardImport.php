<?php


namespace App\Jobs\Import;


use App\Helpers\Helper;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\TicketAwards\Repositories\Interfaces\TicketAwardRepositoryInterface;

class TicketAwardImport
{
    private $headquarter;
    private TicketAwardRepositoryInterface $ticketAwardRepository;

    public function __construct(TicketAwardRepositoryInterface $ticketAwardRepository)
    {
        $this->ticketAwardRepository = $ticketAwardRepository;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/ticket-awards";
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
            $this->ticketAwardRepository->sync($body, $this->headquarter);
        }
    }

}
