<?php

namespace App\Models\Settings;

use App\Models\Headquarters\Headquarter;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'name',
        'config',
        'code_key',
        'headquarter_id'
    ];

    protected $casts = [
      'config' => 'array'
    ];

    public function scopeKey($query, $code){
        return $query->where('code_key', $code);
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }
}
