<?php

namespace App\Http\Controllers\BackOffice\Banners;

use App\Enums\PageWeb;
use App\Helpers\ApiResponse;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Banners\BannerStoreRequest;
use App\Http\Requests\BackOffice\Banners\BannerUpdateRequest;
use App\Http\Resources\BackOffice\Banners\BannerResource;
use App\Models\Banners\Banner;
use App\Models\Banners\Repositories\Interfaces\BannerRepositoryInterface;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use ApiResponser;

    private $bannerRepository;

    public function __construct(
        BannerRepositoryInterface $bannerRepository
    ) {
        $this->bannerRepository = $bannerRepository;

        $this->middleware('permission:read-banner', ['only' => ['index']]);
        $this->middleware('permission:create-banner', ['only' => ['store']]);
        $this->middleware('permission:update-banner', ['only' => ['update']]);
        $this->middleware('permission:delete-banner', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->bannerRepository->search($request->all());
        return BannerResource::collection($data)->additional(['status' => 200]);
    }

    public function store(BannerStoreRequest $request){
        try {
            DB::beginTransaction();
            $banners = new BannerResource($this->bannerRepository->addBanner($request));
            $response = $this->successResponse($banners, 201);
        } catch (Exception $exception) {
            $message = 'Error al añadir banner. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function update(Banner $banner, BannerUpdateRequest $request){
        try {
            DB::beginTransaction();
            $bannerUpdate =  new BannerResource($this->bannerRepository->updateBanner($banner, $request));
            $response = $this->successResponse($bannerUpdate);
        } catch (Exception $exception) {
            $message = 'Error al actualizar banner. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function destroy(Banner $banner){
        try {
            DB::beginTransaction();
            $this->bannerRepository->destroyBanner($banner);
            $response = $this->successResponse(['status' => 200, 'message' => 'Banner eliminado']);
        } catch (Exception $exception) {
            $message = 'Error al eliminar banner. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }
}
