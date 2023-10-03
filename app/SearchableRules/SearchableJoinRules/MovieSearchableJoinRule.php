<?php


namespace App\SearchableRules\SearchableJoinRules;



use App\Services\Searchable\ByColumnSearchable;
use Carbon\Carbon;

class MovieSearchableJoinRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
       return  [
           'is_next_releases' => function($query, $value){
                if($value === 'true'){
                    $query->whereDate('movies.premier_date', '>', Carbon::now()->format('Y-m-d'));
                }else{
                    $query->whereDate('movie_times.start_at', '>=', Carbon::now()->format('Y-m-d'))
                          ->whereDate('movies.premier_date', '<=', Carbon::now()->format('Y-m-d'));

                }
           },
           'headquarter_id' => function($query, $value){
               $query->where('movie_times.headquarter_id', $value);
           },
           'name' => function($query, $value){
                $query->where('movies.name', 'like', '%' . $value . '%');
           },
           'country_id' => ['condition' => '='],
           'city_id' => function($query, $value){
               $query->where('headquarters.city_id', $value);
           },
           'gender_id'=>['condition'=>'=','column'=>'movie_gender_id'],
           'movie_gender_id' => ['condition' => '='],
           'date' => function($query, $value){
                $query->whereDate('movie_times.date_start', '=', $value);
           },
           'to_begin' => function($query, $value){
                $from = Carbon::now();
                $to = $from->copy()->addMinutes(30*$value);
                return $query->whereBetween('movie_times.start_at', [$from, $to]);
           },
       ];
    }
}
