<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class MovieGenderSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'name',
        ];
    }
}
