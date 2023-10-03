<?php

namespace App\Models\Movies\Repositories;

use App\Enums\GlobalEnum;
use App\Helpers\EloquentHelper;
use App\Helpers\FileHelper;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\SearchableRules\MovieSearchableRule;
use App\Services\Searchable\Searchable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Consumer\Movies\MovieResource;
use Illuminate\Support\Facades\Log;

class MovieRepository implements MovieRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Movie $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $movie = $this->model->where('code', $data['code'])->first();
        $is3D = false;

        if(isset($data['is_3d']))
            $is3D = $data['is_3d'] == 1;

        $dataToSave = [
            'code' => $data['code'],
            'is_3d' => $is3D,
            'name' => $data['name'],
            'duration_in_minutes' => $data['minutes'],
            'type_of_censorship' => $data['type_of_censorship'],
            'exclude_igv' => $data['exclude_igv'],
            'exclude_city_tax' => $data['exclude_city_tax'],
            'premier_date' => $data['start_at']
        ];

        if (!$movie) {
            return $this->model->create($dataToSave);
        } else {
            $movie->update($dataToSave);
            return $movie;
        }
    }

    public function all($params)
    {
        $query = $this->queryable();
        $this->searchableService->applyArray($query, new MovieSearchableRule(), $params);
        return $query->get();
    }

    public function search(array $params)
    {
        EloquentHelper::addHeadquarterFilterByAdminRole('headquarter_id', $params, 'param');

        $query = $this->queryable();

        $this->searchableService->applyArray($query, new MovieSearchableRule(), $params);
        return $query->orderBy('premier_date', 'desc')->paginate(Helper::perPage($params));
    }

    public function listMoviesByHeadquarter(int $headquarterId, Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');

        $text = $request->text;
        $date = $request->date;

        return Movie::with('gender', 'country')
            ->whereHas('headquarters', function ($query) use ($headquarterId, $now) {
                return $query->whereDate('start_date', '<=', $now)
                    ->whereDate('end_date', '>', $now)
                    ->where('headquarter_id', $headquarterId);
            })
            ->when($text, function ($q) use ($text) {
                $q->where('name', 'like', '%'.$text.'%');
            })
            ->where('active', 1)
            ->get();
    }

    public function listMoviesSyncMongo($trade_name = null, $code = null)
    {
        $year = date('Y', strtotime('-1 YEAR'));
        $query = Movie::leftJoin('movie_times', 'movies.id', '=' , 'movie_times.movie_id')
        ->leftJoin('headquarters',  function($join) use ($trade_name){
            $join->on('movie_times.headquarter_id', '=', 'headquarters.id')
                ->where('headquarters.trade_name', '=', DB::raw("'". $trade_name."'"));
        })
        ->leftJoin('movie_genders', 'movie_genders.id', '=' , 'movies.movie_gender_id')
        ->leftJoin('countries', 'countries.id', '=' , 'movies.country_id');

        if ($code != null) {
            $query->where('movies.code', $code);
        }

        if ($trade_name !=null){
            $query->where('movies.status', 1);
        }

        $query = $query
            ->whereNull('movie_times.deleted_at')
            ->whereNull('headquarters.deleted_at')
            ->whereYear('movies.created_at','>=', $year)
            ->groupBy('movies.id');

        return $query->get([
                DB::raw("JSON_REPLACE(
               (SELECT JSON_OBJECT(
                       'id', movies.id,
                       'code', movies.code,
                       'is_3d', movies.is_3d,
                       'name', movies.name,
                       'image_path', movies.image_path,
                       'url_trailer', movies.url_trailer,
                       'summary', movies.summary,
                       'duration_in_minutes', movies.duration_in_minutes,
                       'type_of_censorship', movies.type_of_censorship,
                       'premier_date', movies.premier_date,
                       'status', movies.status,
                       'movie_gender_id', movie_genders.id,
                       'movie_gender_name', movie_genders.name,
                       'country_id', countries.id,
                       'country_name', countries.name,
                       'movie_times', null
                   )), '$.movie_times',
                    (SELECT JSON_ARRAYAGG(
                                       JSON_OBJECT(
                                               'headquarter_id', movie_times.headquarter_id,
                                               'start_at', movie_times.start_at,
                                               'date_start', movie_times.date_start,
                                               'city_id', headquarters.city_id
                                           )
                           ))
           ) as result")
        ]);
    }

    public function listMovies($trade_name, $date = null){
//        $movies = Movie::leftJoin('movie_times', function($join) use ($trade_name, $date){
//                $join->on('movie_times.movie_id', '=', 'movies.id')
//                    ->leftJoin('headquarters', function ($join) use ($trade_name) {
//                        $join->on('movie_times.headquarter_id', '=', 'headquarters.id')
//                            ->on('headquarters.trade_name', '=', DB::raw("'".$trade_name."'"));
//                    });
//                if ($date){
//                    $join->whereDate('movie_times.date_start', '=', $date);
//                }
//            })
//            ->join('movie_genders', 'movie_genders.id', '=' , 'movies.movie_gender_id')
//            ->join('countries', 'countries.id', '=' , 'movies.country_id')
//            /*
//            ->whereRaw("CASE WHEN movie_times.movie_id IS NOT NULL
//                             THEN movie_times.date_start >= CURRENT_DATE
//                             ELSE 1 = 1
//                        END")
//            */
//            ->where('movies.status', 1);
        return Movie::leftJoin('movie_times', 'movies.id', '=' , 'movie_times.movie_id')
            ->leftJoin('headquarters',  function($join) use ($trade_name){
                $join->on('movie_times.headquarter_id', '=', 'headquarters.id')
                    ->where('headquarters.trade_name', '=', DB::raw("'". $trade_name."'"));
            })
            ->join('movie_genders', 'movie_genders.id', '=' , 'movies.movie_gender_id')
            ->join('countries', 'countries.id', '=' , 'movies.country_id')
            /*
            ->whereRaw("CASE WHEN movie_times.movie_id IS NOT NULL
                             THEN movie_times.date_start >= CURRENT_DATE
                             ELSE 1 = 1
                        END")
            */
            ->where('movies.status', 1)
            ->whereNull('movie_times.deleted_at')
            ->whereNull('headquarters.deleted_at');
    }

    public function detailMovie($movie){
        $movies = DB::select(
            "SELECT t.id,
                    t.duration_in_minutes,
                    t.image_path,
                    t.name,
                    t.premier_date,
                    t.summary,
                    t.type_of_censorship,
                    t.url_trailer,
                    t.id_movie_gender,
                    t.name_movie_gender,
                    t.id_country,
                    t.name_country
               FROM (SELECT m.id,
                            m.duration_in_minutes,
                            m.image_path,
                            m.name,
                            m.premier_date,
                            m.summary,
                            m.type_of_censorship,
                            m.url_trailer,
                            mg.id AS id_movie_gender,
                            mg.name AS name_movie_gender,
                            c.id AS id_country,
                            c.name AS name_country
                       FROM movies m,
                            movie_genders mg,
                            countries c
                      WHERE m.id = :id_movie
                        AND m.status = 1
                        AND mg.id = m.movie_gender_id
                        AND c.id = m.country_id
                      GROUP BY m.id) t
               GROUP BY t.id,
                        t.duration_in_minutes,
                        t.image_path,
                        t.name,
                        t.premier_date,
                        t.summary,
                        t.type_of_censorship,
                        t.url_trailer,
                        t.id_movie_gender,
                        t.name_movie_gender,
                        t.id_country,
                        t.name_country",
            [
                'id_movie' => $movie->id
            ]
        );
        return Helper::transformSelectToArray($movies)[0];
    }

    public function update($movie, $request)
    {
        if ($request->has('status')) {
            $movie->status = $request->status;
        }
        if ($request->has('url_trailer')) {
            $movie->url_trailer = $request->url_trailer;
        }
        if ($request->has('premier_date')) {
            $movie->premier_date = $request->premier_date;
        }
        if ($request->has('description')) {
            $movie->summary = $request->description;
        }
        if ($request->has('country_id')) {
            $movie->country_id = $request->country_id;
        }
        if ($request->has('gender_id')) {
            $movie->movie_gender_id = $request->gender_id;
        }
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV').GlobalEnum::MOVIES_FOLDER, $request->file('image'));
            $movie->image_path = $file_name;
        }
        $movie->save();
        return $movie;
    }
}
