<?php


namespace App\Http\Controllers\API\Products;


use App\Http\Controllers\ApiController;
use App\Http\Requests\API\Products\ProductTypeRequest;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Models\ProductTypes\Repositories\Interfaces\ProductTypeRepositoryInterface;

class ProductTypeController extends ApiController
{
    private ProductTypeRepositoryInterface $productTypeRepository;

    public function __construct(ProductTypeRepositoryInterface $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    public function indexByProduct(ProductTypeRequest $request)
    {
        $data = $this->productTypeRepository->allByHeadquarter($request->headquarter_id);
        $data = ListCollection::collection($data);
        return $this->success($data);
    }

    public function indexByCombo(ProductTypeRequest $request)
    {
        $data = $this->productTypeRepository->allByHeadquarter($request->headquarter_id, true);
        $data = ListCollection::collection($data);
        return $this->success($data);
    }

    public function indexByPromotion(ProductTypeRequest $request)
    {
        $movie_time_id = $request->has('movie_time_id') ? $request->movie_time_id : null;
        $data = $this->productTypeRepository->allByHeadquarterPromotion($request->headquarter_id, $movie_time_id);
        return $this->success($data);
    }

}
