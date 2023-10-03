<?php


namespace App\Models\JobApplication\Repositories;


use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Helpers\Helper;
use App\Jobs\SendJobAppEmail;
use App\Models\JobApplication\JobApplication;
use App\Models\JobApplication\Repositories\Interfaces\JobApplicationRepositoryInterface;

class JobApplicationRepository implements JobApplicationRepositoryInterface
{
    public function create($body, $file)
    {
        $folderName = env('BUCKET_ENV').GlobalEnum::JOB_APPLICATIONS_FOLDER;
        $guid =  FileHelper::saveFile($folderName, $file);

        // create model
        $body['trade_name'] = Helper::getTradeNameHeader();
        $dataValidated = $body;

        if($file)
        {
            $dataValidated['file_guid'] = $guid;
            $dataValidated['file_name'] = $file->getClientOriginalName();
        }

        $model = JobApplication::create($dataValidated);

        // send email
        SendJobAppEmail::dispatch($model);

        return $model;
    }
}
