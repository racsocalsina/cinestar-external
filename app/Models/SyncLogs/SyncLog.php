<?php


namespace App\Models\SyncLogs;


use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    public $timestamps = false;
    protected $dates = ['sync_start_datetime', 'sync_end_datetime'];
    protected $guarded = ['id'];
}
