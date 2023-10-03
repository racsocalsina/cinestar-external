<?php


namespace App\Models\JobApplication;


use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['birth_date'];
}
