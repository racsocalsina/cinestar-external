<?php


namespace App\Http\Resources\BackOffice\Headquarters;


use App\Enums\BusinessName;
use App\Enums\GlobalEnum;
use App\Helpers\CastNameHelper;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class HeadquarterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'description'            => $this->description,
            'address'                => $this->address,
            'latitude'               => $this->latitude,
            'longitude'              => $this->longitude,
            'point_sale'             => $this->point_sale,
            'api_url'                => $this->api_url,
            'local_url'              => $this->local_url,
            'user'                   => $this->user,
            'city_id'                => $this->city_id,
            'status'                 => $this->status == 1,
            'status_name'            => CastNameHelper::getEnabledName($this->status),
            'movie_formats'          => $this->movie_formats,
            'business_name'          => $this->business_name,
            'business_friendly_name' => BusinessName::getNameByBusinessName($this->business_name),
            'trade_name'             => $this->trade_name,
            'headquarter_images'     => $this->headquarter_images,
            'last_sync_log'          => $this->getLastSyncLog(),
        ];
    }

    private function getLastSyncLog()
    {

        $lastSyncLog = $this->sync_logs ? $this->sync_logs->sortByDesc('id')->first() : null;

        if ($lastSyncLog) {
            return [
                'status'              => $lastSyncLog->status,
                'status_name'         => CastNameHelper::getSyncStatusName($lastSyncLog->status),
                'sync_start_datetime' => ($lastSyncLog->sync_start_datetime ? Helper::getDateTimeFormat($lastSyncLog->sync_start_datetime, 'd/m/y h:i:s a') : null),
                'sync_end_datetime'   => ($lastSyncLog->sync_end_datetime ? Helper::getDateTimeFormat($lastSyncLog->sync_end_datetime, 'd/m/y h:i:s a') : null)
            ];
        }

    }
}
