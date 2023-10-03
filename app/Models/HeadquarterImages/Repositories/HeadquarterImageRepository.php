<?php


namespace App\Models\HeadquarterImages\Repositories;


use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Models\HeadquarterImages\HeadquarterImage;
use App\Models\HeadquarterImages\Repositories\Interfaces\HeadquarterImageRepositoryInterface;
use App\Models\Headquarters\Headquarter;
use Illuminate\Support\Facades\Storage;

class HeadquarterImageRepository implements HeadquarterImageRepositoryInterface
{
    private $model;

    public function __construct(HeadquarterImage $model)
    {
        $this->model = $model;
    }

    public function delete(HeadquarterImage $model)
    {
        $model->delete();
    }

    public function markAsMain(HeadquarterImage $model)
    {
        $this->model->where('headquarter_id', $model->headquarter_id)
            ->update(['is_main_image' => 0]);

        $model->update(['is_main_image' => 1]);

        return $model;
    }

    public function create(Headquarter $headquarter, array $images)
    {
        $this->saveImages($headquarter, $images, false);
    }

    public function saveImages(Headquarter $headquarter, array $images, $markAsMain = true) {
        $counter = 0;

        foreach ($images as $image) {

            $isMainImage = false;

            if($markAsMain)
                $isMainImage = $counter == 0;

            $filename = FileHelper::saveFile(env('BUCKET_ENV').GlobalEnum::HEADQUARTERS_FOLDER, $image);

            $add = [
                'path' => $filename,
                'is_main_image' => $isMainImage
            ];

            $headquarter->headquarter_images()->create($add);

            $counter++;
        }
    }
}
