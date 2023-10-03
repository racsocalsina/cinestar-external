<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class InternalErrorSearchableRule implements ByColumnSearchable
{
    public function searchableColumns(): array
    {
        return [
            'headquarter_id',
            'actionable',
            'action_realized' => ['condition' => '='],
            'start_date' => function($query, $value){
                $query->whereDate('created_at', '>=', $value);
            },
            'end_date' => function($query, $value){
                $query->whereDate('created_at', '<=', $value);
            },
        ];
    }
}
