<?php


namespace App\Models\SyncLogs\Repositories\Interfaces;


use App\Models\Headquarters\Headquarter;
use App\Models\SyncLogs\SyncLog;

interface SyncLogRepositoryInterface
{
    public function create(Headquarter $headquarter);
    public function update(Headquarter $headquarter, $status);
}
