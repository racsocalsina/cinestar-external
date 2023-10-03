<?php


namespace App\Http\Controllers\BackOffice\Masters;


use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Models\ProductTypes\Repositories\Interfaces\ProductTypeRepositoryInterface;
use App\Traits\ApiResponser;

class MasterProductTypeController extends Controller
{
    use ApiResponser;

    private ProductTypeRepositoryInterface $productTypeRepository;

    public function __construct(ProductTypeRepositoryInterface $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    public function indexByProduct()
    {
        $data = ListCollection::collection($this->productTypeRepository->allByType());
        return $this->success($data);
    }

    public function indexByCombo()
    {
        $data = ListCollection::collection($this->productTypeRepository->allByType(true));
        return $this->success($data);
    }
}
