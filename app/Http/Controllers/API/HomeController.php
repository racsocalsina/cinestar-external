<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Resources\BackOffice\Banners\BannerResource;
use App\Models\Banners\Repositories\Interfaces\BannerRepositoryInterface;
use Illuminate\Http\Request;


class HomeController extends ApiController
{
    private $bannerRepository;

    public function __construct(
        BannerRepositoryInterface $bannerRepository
    ) {
        $this->bannerRepository = $bannerRepository;
    }

    public function getBanners(Request $request){
        $pages = $request->has('pages') ? $request->pages : null ;
        $banners = BannerResource::collection($this->bannerRepository->getBanners($pages));
        return $this->successResponse($banners);
    }

    public function updateBanner(){
        $banners = $this->bannerRepository->getBanners();
        return $this->successResponse($banners);
    }
}
