<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class ChocoAwardSearchableRule  implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'code',
            'name',
        ];
    }
}
