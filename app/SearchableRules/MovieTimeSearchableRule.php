<?php


namespace App\SearchableRules;



use App\Services\Searchable\ByColumnSearchable;
use Carbon\Carbon;

class MovieTimeSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return  [
            'movie_id' => function($query, $value){
                $query->where('movie_id', '=', $value);
            },
            'headquarter_id' => function($query, $value){
                $query->where('headquarter_id', '=', $value);
            },
            'name' => function($query, $value){
                $query->whereHas('headquarter', function ($sub_query) use ($value) {
                    return $sub_query->where('name', 'LIKE', "%$value%");
                });
            },
            'city_id' => function($query, $value){
                $query->whereHas('headquarter', function ($sub_query) use ($value) {
                    return $sub_query->where('city_id', '=', $value);
                });
            },
            'date' => function($query, $value){
                $query->whereDate('date_start', '=', $value);
            },
            'ids' => function($query, $value){
                $query->whereIn('id', explode(',', $value));
            },
            'movie_name' => function($query, $value){
                $query->whereHas('movie', function ($sub_query) use ($value) {
                    return $sub_query->where('name', 'LIKE', "%$value%");
                });
            },
        ];
    }
}
