<?php

namespace App\Models\AutoIncrementCodes;

use Illuminate\Database\Eloquent\Model;

class AutoIncrementCode extends Model
{
     protected $fillable = ['code', 'current', 'business_name' ];

}
