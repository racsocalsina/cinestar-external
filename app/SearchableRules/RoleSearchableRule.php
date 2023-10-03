<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class RoleSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'display_name',
        ];
    }
}
