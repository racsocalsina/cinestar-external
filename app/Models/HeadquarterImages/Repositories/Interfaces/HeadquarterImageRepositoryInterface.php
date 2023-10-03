<?php


namespace App\Models\HeadquarterImages\Repositories\Interfaces;


use App\Models\HeadquarterImages\HeadquarterImage;
use App\Models\Headquarters\Headquarter;

interface HeadquarterImageRepositoryInterface
{
    public function create(Headquarter $headquarter, array $images);
    public function delete(HeadquarterImage $model);
    public function markAsMain(HeadquarterImage $model);
    public function saveImages(Headquarter $headquarter, array $images, $markAsMain = true);
}
