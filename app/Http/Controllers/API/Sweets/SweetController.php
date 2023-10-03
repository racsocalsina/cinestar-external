<?php


namespace App\Http\Controllers\API\Sweets;


use App\Http\Controllers\ApiController;
use App\Http\Requests\API\Sweets\SweetRequest;
use App\Http\Requests\API\Sweets\SweetsFavoriteRequest;
use App\Http\Resources\API\Sweets\SweetResource;
use App\Models\Products\Repositories\Interfaces\SweetRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SweetController extends ApiController
{
    private SweetRepositoryInterface $sweetRepository;

    public function __construct(SweetRepositoryInterface $sweetRepository)
    {
        $this->sweetRepository = $sweetRepository;
    }

    public function index(SweetRequest $request)
    {
        $params = $request->all();
        $params['user_id'] = Auth::user()->id;
        if(!isset($request->presale_date)){ $params['presale_date'] = now()->format('Y-m-d');};
        $data = $this->sweetRepository->searchFavoriteApi($params);
        $data = SweetResource::collection($data);
        return $this->success($data);
    }

    public function toggleFavorite(SweetsFavoriteRequest $request)
    {
        $params = $request->all();
        $params['user_id'] = Auth::user()->id;
        $this->sweetRepository->toggleFavorite($params);
        return  $this->successResponse(['message' => 'Favorito actualizado']);
    }

}
