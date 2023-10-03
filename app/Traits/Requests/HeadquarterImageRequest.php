<?php


namespace App\Traits\Requests;


trait HeadquarterImageRequest
{
    protected function checkImages($headquarter, $files)
    {
        $limitAllowedImages = 5;
        $allowedMimeTypes = ['image/jpeg','image/jpg','image/png'];
        $fileSizeAllowed = 5120; // 5mb

        foreach ($files as $file)
        {
            if(!in_array($file->getClientMimeType(), $allowedMimeTypes))
            {
                return ['status' => 422, 'message' => __('app.headquarters.image_mime_type_not_valid', ['name' => $file->getClientOriginalName()])];
            }

            $fileSize = $file->getSize() / 1024;

            if($fileSize > $fileSizeAllowed){
                return
                    ['status' => 422, 'message' => __('app.headquarters.image_file_size_exceeded', [
                            'name' => $file->getClientOriginalName(),
                            'size' => '5mb'
                    ])
                ];
            }
        }

        if(count($files) == 0)
            return ['status' => 422, 'message' => __('app.headquarters.no_images_uploaded')];

        // check total images
        if(is_null($headquarter)) {
            $totalImages = count($files);

            if ($totalImages > $limitAllowedImages)
                return ['status' => 422, 'message' => __('app.headquarters.images_limit_exceeded', ['limit' => $limitAllowedImages])];
        } else {
            $imagesAlreadyUpload = count($headquarter->headquarter_images);
            $totalImages = $imagesAlreadyUpload + count($files);

            if ($totalImages > $limitAllowedImages)
                return ['status' => 422, 'message' => __('app.headquarters.images_limit_exceeded', ['limit' => $limitAllowedImages])];
        }

        return true;

    }

}
