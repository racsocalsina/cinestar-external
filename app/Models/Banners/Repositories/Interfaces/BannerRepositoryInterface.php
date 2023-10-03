<?php

namespace App\Models\Banners\Repositories\Interfaces;

interface BannerRepositoryInterface
{
    public function getBanners($pages = null);
    public function search(array $params);
    public function addBanner($request);
    public function updateBanner($banner, $request);
    public function destroyBanner($request);
}
