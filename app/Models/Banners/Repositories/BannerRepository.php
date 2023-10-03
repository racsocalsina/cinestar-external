<?php

namespace App\Models\Banners\Repositories;

use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Helpers\Helper;
use App\Models\Banners\Banner;
use App\Models\Banners\Repositories\Interfaces\BannerRepositoryInterface;
use App\SearchableRules\BannerSearchableRule;
use App\Services\Searchable\Searchable;

class BannerRepository implements BannerRepositoryInterface
{
    private $searchableService;

    public function __construct(Searchable $searchableService)
    {
        $this->searchableService = $searchableService;
    }

    public function getBanners($page = null)
    {
        $query = Banner::query();
        return $page ? $query->where('page', $page)->get()  : $query->get();
    }

    public function search(array $params)
    {
        $query = Banner::query();
        $this->searchableService->applyArray($query, new BannerSearchableRule(), $params);

        return $query->orderBy('id', 'desc')->paginate(Helper::perPage($params));
    }

    public function addBanner($request)
    {
        $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::BANNERS_FOLDER, $request->file('image'));
        return Banner::create([
            'link'         => $request->link,
            'type'         => $request->type,
            'path'         => $file_name,
            'trade_name'   => $request->trade_name,
            'download_app' => $request->download_app === 'true',
            'page' => $request->page
        ]);
    }

    public function updateBanner($banner, $request)
    {
        if ($request->has('link')) {
            $banner->link = $request->link;
        }
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::BANNERS_FOLDER, $request->file('image'));
            $banner->path = $file_name;
        }
        if ($request->has('type')) {
            $banner->type = $request->type;
        }
        if ($request->has('trade_name')) {
            $banner->trade_name = $request->trade_name;
        }
        if ($request->has('download_app')) {
            $banner->download_app = $request->download_app === 'true';
        }
        if ($request->has('page')) {
            $banner->page = $request->page;
        }
        $banner->save();
        return $banner;
    }

    public function destroyBanner($request)
    {
        $banner = Banner::find($request->id);
        FileHelper::deleteFile(env('BUCKET_ENV') . GlobalEnum::BANNERS_FOLDER, $banner->image);
        $banner->delete();
    }
}
