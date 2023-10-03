<?php


namespace App\Http\Controllers\API\Products;


use App\Helpers\FunctionHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\API\Products\ProductRequest;
use App\Http\Resources\API\Products\ProductPromotionResource;
use App\Http\Resources\API\Products\ProductResource;
use App\Models\Products\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProductController extends ApiController
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function indexByProduct(ProductRequest $request)
    {
        $user = FunctionHelper::getApiUser();
        $params = $request->all();
        $params['user_id'] = $user ? $user->id : null;
        $params['is_combo'] = false;
        if(!isset($request->presale_date)){ $params['presale_date'] =  now()->format('Y-m-d');};
        $data = $this->productRepository->searchApi($params);
        $data = ProductResource::collection($data);
        return $this->success($data);
    }

    public function indexByCombo(ProductRequest $request)
    {
        $user = FunctionHelper::getApiUser();
        $params = $request->all();
        $params['user_id'] = $user ? $user->id : null;
        $params['is_combo'] = true;
        if(!isset($request->presale_date)){ $params['presale_date'] = now()->format('Y-m-d');};
        $data = $this->productRepository->searchApi($params);
        $data = ProductResource::collection($data);
        return $this->success($data);
    }

    public function indexByPromotion(ProductRequest $request)
    {
        $movie_time_id = $request->has('movie_time_id') ? $request->movie_time_id : null;
        $product_type_id = $request->has('product_type_id') ? $request->product_type_id : null;
        $today = $request->has('today') ? $request->today : null;
        $data = $this->productRepository->searchApiPromotions($request->headquarter_id, $movie_time_id, $product_type_id,$today);
        foreach ($data as $item){
            $price = $this->productRepository->applicatePromotionPrice($request->headquarter_id, $item->product, $item);
            $item->price = $price;
        }
        $data = ProductPromotionResource::collection($data);
        return $this->success($data);
    }

}
