<?php


namespace App\Models\Cards;


use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = ['id'];

    public function getBinAttribute($query)
    {
        return substr($this->masked_number, 0, 6);
    }
}
