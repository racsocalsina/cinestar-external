<?php

namespace App\Models\FailedJobs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedJobs extends Model
{
    use HasFactory;

    protected $table = 'failed_jobs';

    protected $fillable = [
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at'
    ];
}
