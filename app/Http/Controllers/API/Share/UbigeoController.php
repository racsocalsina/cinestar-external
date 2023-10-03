<?php


namespace App\Http\Controllers\API\Share;



use App\Http\Resources\UbigeoCollection;
use App\Models\Ubigeo\Repositories\Interfaces\UbigeoRepositoryInterface;

class UbigeoController
{
    private $ubigeoRepository;

    public function __construct(UbigeoRepositoryInterface $ubigeoRepository)
    {
        $this->ubigeoRepository = $ubigeoRepository;
    }

    public function index()
    {
        $data = $this->ubigeoRepository->all();
        return [
            'data' => UbigeoCollection::collection($data),
        ];
    }
}
