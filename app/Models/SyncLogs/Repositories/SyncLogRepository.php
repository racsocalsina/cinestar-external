<?php


namespace App\Models\SyncLogs\Repositories;


use App\Enums\GlobalEnum;
use App\Models\Headquarters\Headquarter;
use App\Models\SyncLogs\Repositories\Interfaces\SyncLogRepositoryInterface;
use App\Models\SyncLogs\SyncLog;

class SyncLogRepository implements SyncLogRepositoryInterface
{
    private $model;

    public function __construct(SyncLog $model)
    {
        $this->model = $model;
    }

    public function create(Headquarter $headquarter)
    {
        SyncLog::where('headquarter_id', $headquarter->id)->delete();

        $data = [
            'headquarter_id'      => $headquarter->id,
            'sync_start_datetime' => now(),
        ];

        return $this->model::create($data);
    }

    public function update(Headquarter $headquarter, $status)
    {
        $dataToUpdateOrCreate = [
            'status' => $status
        ];

        if ($status != GlobalEnum::SYNC_LOG_STATUS_SYNCING)
            $dataToUpdateOrCreate['sync_end_datetime'] = now();

        $model = SyncLog::where('headquarter_id', $headquarter->id)->first();

        if(!$model)
        {
            return $this->model::create($dataToUpdateOrCreate);
        } else
        {
            $model->update($dataToUpdateOrCreate);
            return $model;
        }
    }
}
