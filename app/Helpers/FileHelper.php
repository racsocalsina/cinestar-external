<?php


namespace App\Helpers;


use App\Enums\GlobalEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function getExtensionFromFilename($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public static function getFileGuidExtension($fileGuid, $filename)
    {
        if (!($fileGuid && $filename))
            return null;

        $extension = self::getExtensionFromFilename($filename);
        return "{$fileGuid}.{$extension}";
    }

    public static function saveFileStorage($folder, $fileName, $file)
    {
        $path = $folder . '/' . $fileName;
        Storage::disk()->put($path, File::get($file));
    }

    public static function deleteFile($folder, $filename): void
    {
        Storage::delete($folder . "/" . $filename);
    }

    public static function saveFile($folder, $file)
    {
        $ext = $file->extension();
        $date = Carbon::now()->timestamp;
        $file_name = "$date.$ext";
        Storage::putFileAs($folder, $file, $file_name);
        return $file_name;
    }

    public static function saveFileById($folder, $file, $uuid): string
    {
        $ext = $file->extension();
        $fileName = "$uuid.$ext";
        Storage::putFileAs($folder, $file, $fileName);
        return $fileName;
    }

    public static function getFileNameFromFullPathUrl($fullPathUrl): string
    {
        return substr($fullPathUrl, strrpos($fullPathUrl, '/') + 1);
    }

    public static function getFileUrl($bucketFolder, $fileName): string
    {
        return config('constants.path_images') . env('BUCKET_ENV') . $bucketFolder . "/" . $fileName;
    }
}
