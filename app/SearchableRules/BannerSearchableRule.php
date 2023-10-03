<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class BannerSearchableRule implements ByColumnSearchable
{
    public function searchableColumns(): array
    {
        return  [
            'type' => ['condition' => '='],
            'trade_name' => ['condition' => '='],
            'page_name' => function($query, $value){
                $query->where('page', '=', $value);
            },
        ];
    }
}
