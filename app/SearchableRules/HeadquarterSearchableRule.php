<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class HeadquarterSearchableRule implements ByColumnSearchable
{
    public function searchableColumns(): array
    {
        return [
            'name',
            'movie_format_id' => function ($query, $value) {
                $query->whereHas('movie_formats', function ($sub_query) use ($value) {
                    return $sub_query->where('movie_format_id', $value);
                });
            },
            'city_id'         => ['condition' => '='],
            'business_name'   => ['condition' => '='],
        ];
    }
}
