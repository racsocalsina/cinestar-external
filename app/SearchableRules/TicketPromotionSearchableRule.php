<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class TicketPromotionSearchableRule implements ByColumnSearchable
{
    public function searchableColumns(): array
    {
        return  [
            'code' => function($query, $value){
                $query->where('code', 'like', '%' . $value . '%');
            },
            'name' => function($query, $value){
                $query->where('name', 'like', '%'.$value.'%');
            },
            'movie_chain' => ['condition' => '='],
            'headquarter_name' => function ($query, $value) {
                $query->whereHas('headquarter', function ($q) use ($value) {
                    return $q->where('name', 'like', '%'.$value.'%');
                });
            },
            'headquarter_id' => ['condition' => '='],
            'start_date' => function($query, $value){
                $query->where('start_date', '>=', $value);
            },
            'end_date' => function($query, $value){
                $query->where('end_date', '<=', $value);
            },
            'valid' =>  function($query, $value) {
                if ($value == 1 || $value == true) {
                    $query->whereRaw('end_date >= now()');
                } else if ($value == 0 || $value == false) {
                    $query->whereRaw('end_date < now()');
                }
            },
            'type' => function ($query, $value) {
                if($value == 0) {
                    $query->whereHas('award');
                } else {
                    $query->whereDoesntHave('award');
                }
            },
        ];
    }
}
