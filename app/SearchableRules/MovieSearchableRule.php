<?php


namespace App\SearchableRules;



use App\Services\Searchable\ByColumnSearchable;
use Carbon\Carbon;

class MovieSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
       return  [
           'is_next_releases' => function($query, $value){
                if($value === 'true'){
                    $query->whereDate('premier_date', '>', Carbon::now()->format('Y-m-d'));
                }else{
                    $query->whereDate('premier_date', '<=', Carbon::now()->format('Y-m-d'));
                }
           },
           'headquarter_id' => function($query, $value){
                $query->whereHas('movie_times', function ($sub_query) use ($value) {
                    return $sub_query->where('headquarter_id', $value);
                });
           },
           'name' => function($query, $value){
                $query->where('movies.name', 'like', '%' . $value . '%');
           },
           'country_id' => ['condition' => '='],
           'city_id' => function($query, $value){
                $query->whereHas('movie_times.headquarter', function ($sub_query) use ($value) {
                    return $sub_query->where('city_id', $value);
                });
           },
           'trade_name' => function ($query, $value) {
            $query->whereHas('movie_times.headquarter', function ($sub_query) use ($value) {
                return $sub_query->where('trade_name', $value);
            });
            },
            'status' => function ($query, $value) {
                $status = strtolower($value);
                if ($status === 'true') {
                    $query->where('status', true);
                }
            },
           'gender_id'=>['condition'=>'=','column'=>'movie_gender_id'],
           'movie_gender_id' => ['condition' => '='],
           'date' => function($query, $value){
                $query->whereHas('movie_times', function ($sub_query) use ($value) {
                    return $sub_query->whereDate('date_start', '=', $value);
                });
           },
           'to_begin' => function($query, $value){
                $from = Carbon::now();
                $to = $from->copy()->addMinutes(30*$value);
                $query->whereHas('movie_times', function ($sub_query) use ($from, $to) {
                    return $sub_query->whereBetween('start_at', [$from, $to]);
                });
           },
       ];
    }
}
