<?php

namespace App\Jobs\Import;

use App\Helpers\Helper;
use App\Http\Controllers\API\MoviesController;
use App\Jobs\Import\Utils\HelperImport;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Response;

class MovieImport
{
    private $headquarter;
    private MovieRepositoryInterface $movieRepository;
    private $movieController;
    protected $request;

    public function __construct(MovieRepositoryInterface $movieRepository,
    MoviesController $movieController,
    Request $request)
    {
        $this->movieRepository = $movieRepository;
        $this->movieController = $movieController;
        $this->request = $request;
    }

    public function execute($token, $headquarter)
    {
        $this->headquarter = $headquarter;
        $url = Helper::addSlashToUrl($headquarter->api_url) . "api/v1/consumer/community-movies";
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
            $this->movieRepository->sync($body, $this->headquarter);
        }
    }
}
