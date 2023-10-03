<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class TicketAwardSearchableRule  implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'code',
            'name',
        ];
    }
}
