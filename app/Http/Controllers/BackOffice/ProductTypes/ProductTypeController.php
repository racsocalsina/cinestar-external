<?php


namespace App\Http\Controllers\BackOffice\ProductTypes;


use App\Http\Controllers\Controller;
use App\Models\ProductTypes\Repositories\Interfaces\ProductTypeRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductTypeController extends Controller
{
    use ApiResponser;

    private ProductTypeRepositoryInterface $productTypeRepository;

    public function __construct(ProductTypeRepositoryInterface $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    public function sync(Request $request){
        try {
            DB::beginTransaction();

            $body = $request->all();
            $this->productTypeRepository->sync($body);
            $response = $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }
}
