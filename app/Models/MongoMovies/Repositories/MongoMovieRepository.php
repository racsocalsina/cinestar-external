<?php

namespace App\Models\MongoMovies\Repositories;

use App\Models\MongoMovies\MongoMovie;
use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Resources\Consumer\MongoMovies\MongoMovieResource;

class MongoMovieRepository implements MongoMovieRepositoryInterface
{
    private $model;

    public function __construct(MongoMovie $model)
    {
        $this->model = $model;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function test(): JsonResponse
    {
        $blogs = MongoMovie::all();
        return response()->json($blogs);
    }

    public function search(array $params)
    {
        $query = MongoMovie::query();

        if (isset($params['name']))
        {
            $name = $params['name'];
            if ($name)
            {
                $query->where('name', 'like', '%' . $name . '%');
            }
        }

        if (isset($params['gender_id']))
        {
            $gender_id = (int)$params['gender_id'];
            if ($gender_id)
            {
                $query->Where('movie_gender_id', $gender_id);
            }
        }

        if (isset($params['country_id']))
        {
            $country_id = (int)$params['country_id'];
            if ($country_id)
            {
                $query->Where('country_id', $country_id);
            }
        }

        if (isset($params['premier_date']))
        {
            $premier_date = $params['premier_date'];
            if ($premier_date)
            {
                $query->Where('premier_date', $premier_date);
            }
        }
        if (isset($params['is_next_releases']))
        {
            $is_next_releases = $params['is_next_releases'];
            if ($is_next_releases === 'true')
            {
                $query->whereDate('premier_date', '>', Carbon::now()->format('Y-m-d'));
                $query->where('status',1);
            }
            else
            {
                $query->whereDate('movie_times.start_at', '>=', Carbon::now()->format('Y-m-d'))
                ->whereDate('premier_date', '<=', Carbon::now()->format('Y-m-d'));
                $query->where('status',1);
            }
        }

        if (isset($params['headquarter_id']))
        {
            $headquarter_id = (int)$params['headquarter_id'];
            if ($headquarter_id)
            {
                $query->Where('movie_times.headquarter_id', $headquarter_id);
            }
        }

        if (isset($params['date']))
        {
            $date = $params['date'];
            if ($date)
            {
                $query->whereDate('movie_times.date_start', '=', $date);
            }
        }

        if (isset($params['to_begin']))
        {
            $to_begin = $params['to_begin'];
            if ($to_begin)
            {
                $from = Carbon::now();
                $to = $from->copy()->addMinutes(30*$to_begin);
                $query->whereBetween('movie_times.start_at', [$from, $to]);
            }
        }

        $users = MongoMovieResource::collection(
            $query
                ->get([
                    'id',
                    'code',
                    'is_3d',
                    'name',
                    'image_path',
                    'url_trailer',
                    'summary',
                    'duration_in_minutes',
                    'type_of_censorship',
                    'premier_date',
                    'status',
                    'movie_gender_id',
                    'movie_gender_name',
                    'country_id',
                    'country_name'
                ])
        );
        return $users;
    }

    public function savemovies(array $movies, $trade_name): bool
    {
        Log::info(json_encode($movies, true));
        try
        {

            DB::beginTransaction();
            foreach($movies as $dato)
            {
                $data = json_decode($dato, true);
                MongoMovie::where('id', $data['id'])
                    ->update(
                        [
                            'code'                  => $data['code'],
                            'name'                  => $data['name'],
                            'image_path'            => $data['image_path'],
                            'url_trailer'           => $data['url_trailer'],
                            'summary'               => $data['summary'],
                            'duration_in_minutes'   => $data['duration_in_minutes'],
                            'type_of_censorship'    => $data['type_of_censorship'],
                            'premier_date'          => $data['premier_date'],
                            'status'                => $data['status'],
                            'movie_gender_id'       => $data['movie_gender_id'],
                            'movie_gender_name'     => $data['movie_gender_name'],
                            'country_id'            => $data['country_id'],
                            'country_name'          => $data['country_name'],
                            'movie_times'           => $data['movie_times'],
                            'trade_name'            => $trade_name
                        ],
                        ['upsert' => true]
                    );
            }
            $value = true;
        }
        catch (Exception $e)
        {
            DB::rollBack();
            $value = false;
            Log::error("Error en savemovies mongodb ".$e);
        }
        DB::commit();

        return $value;
    }
}
