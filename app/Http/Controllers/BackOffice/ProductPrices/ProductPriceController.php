<?php


namespace App\Http\Controllers\BackOffice\ProductPrices;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\ProductPrice\ProductPriceSyncRequest;
use App\Models\ProductPrices\Repositories\Interfaces\ProductPriceRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    use ApiResponser;

    private ProductPriceRepositoryInterface $repository;

    public function __construct(ProductPriceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function sync(ProductPriceSyncRequest $request){
        try {
            DB::beginTransaction();
            $this->repository->sync($request->all());
            $response = $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }
}
