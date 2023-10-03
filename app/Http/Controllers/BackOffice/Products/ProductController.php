<?php


namespace App\Http\Controllers\BackOffice\Products;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Products\ProductUpdateRequest;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterListResource;
use App\Http\Resources\BackOffice\Products\ProductHeadquarterCollection;
use App\Http\Resources\BackOffice\Products\ProductResource;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Products\Product;
use App\Models\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiResponser;

    private ProductRepositoryInterface $productRepository;
    private PurchaseRepositoryInterface $purchaseRepository;
    private HeadquarterRepositoryInterface $headquarterRepository;

    public function __construct(ProductRepositoryInterface $productRepository, HeadquarterRepositoryInterface $headquarterRepository,
                                PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->productRepository = $productRepository;
        $this->headquarterRepository = $headquarterRepository;
        $this->purchaseRepository = $purchaseRepository;

        $this->middleware('permission:read-product', ['only' => ['index']]);
        $this->middleware('permission:update-product', ['only' => ['update', 'sync']]);
        $this->middleware('permission:read-reports', ['only' => ['getTotals', 'getParameters']]);
    }
    //Funcion mediante el cual se realiza la sincronización
    public function sync(Request $request){
        try {
            DB::beginTransaction();

            $body = $request->all();
            $this->productRepository->sync($body);
            $response = $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }

    public function indexByProduct(Request $request)
    {
        $params = $request->all();
        $params['is_combo'] = false;
        $data = $this->productRepository->searchBo($params);
        return ProductResource::collection($data)->additional(['status' => 200]);
    }

    public function getAvailableHeadquartersByProduct(Product $product)
    {
        $data = $this->productRepository->getAvailableHeadquarters($product);
        return ProductHeadquarterCollection::collection($data)->additional(['status' => 200]);
    }

    public function update(Product $product, ProductUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = new ProductResource($this->productRepository->update($product, $request));
            $response = $this->successResponse($data);
        } catch (\Exception $exception) {
            $message = 'Error al actualizar. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function indexByCombo(Request $request)
    {
        $params = $request->all();
        $params['is_combo'] = true;
        $data = $this->productRepository->searchBo($params);
        return ProductResource::collection($data)->additional(['status' => 200]);
    }

    public function getAvailableHeadquartersByCombo(Product $product)
    {
        $data = $this->productRepository->getAvailableHeadquarters($product, true);
        return ProductHeadquarterCollection::collection($data)->additional(['status' => 200]);
    }

    public function getTotals(Request $request)
    {
        $data = $this->productRepository->getTotals($request);
        return $this->successResponse($data);
    }

    public function getParameters(Request $request)
    {
        $headquarters = $this->headquarterRepository->all();
        $filter = $request['headquarter_id'] ? ['headquarter_id' => $request['headquarter_id']] : [];
        $products = $this->productRepository->search($filter);
        $schedules = $this->purchaseRepository->getSchedules($request);
        $data = [
            'headquarters'   => HeadquarterListResource::collection($headquarters),
            'products'  => ProductResource::collection($products),
            'schedules'  => $schedules,
        ];
        return $this->success($data);
    }
}
