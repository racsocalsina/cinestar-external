<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class CitySearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'name',
            'trade_name'
        ];
    }
}
