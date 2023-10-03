<?php

namespace App\Models\JobTrigger;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTrigger extends Model
{
    use HasFactory;

    protected $fillable = ['origin', 'origin_id', 'status', 'type', 'executed_date', 'description'];

}
