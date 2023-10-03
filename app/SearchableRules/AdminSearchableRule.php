<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class AdminSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'name',
            'lastname',
            'email',
            'status' => ['condition' => '='],
            'role' => function($query, $value){
                $query->whereHas('roles', function ($sub_query) use ($value) {
                    return $sub_query->where('name', '=', $value);
                });
            },
            'document_type_id' => ['condition' => '='],
            'document_number',
            'headquarter_id' => ['condition' => '='],
        ];
    }
}
