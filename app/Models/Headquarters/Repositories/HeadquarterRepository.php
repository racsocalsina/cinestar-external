<?php


namespace App\Models\Headquarters\Repositories;


use App\Helpers\EloquentHelper;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\Movies\Movie;
use App\SearchableRules\HeadquarterSearchableRule;
use App\Services\Searchable\Searchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Headquarters\TestModel;
use Illuminate\Http\JsonResponse;

class HeadquarterRepository implements HeadquarterRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Headquarter $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function search(array $params, $pagination = true)
    {
        $query = $this->queryable();
        EloquentHelper::addHeadquarterFilterByAdminRole('id', $query);

        $query = $query->with(['movie_formats', 'headquarter_images', 'sync_logs']);
        $this->searchableService->applyArray($query, new HeadquarterSearchableRule(), $params);

        if($pagination)
            return $query->orderBy('name')->paginate(Helper::perPage($params));
        else
            return $query->orderBy('name')->get();
    }

    public function get(int $id)
    {
        $query = $this->queryable();
        EloquentHelper::addHeadquarterFilterByAdminRole('id', $query);

        return $query->where('id', $id)->first();
    }

    public function create(array $params): Headquarter
    {
        // vars
        $fieldsToCreate = $params['fields'];
        $movieFormats = $params['movie_formats'];

        // headquarters
        $fieldsToCreate['password'] = encrypt($fieldsToCreate['password']);
        $headquarter = Headquarter::create($fieldsToCreate);

        // headquarters movie format
        $headquarter->movie_formats()->sync($movieFormats);

        return $headquarter;
    }

    public function update(Headquarter $model, array $data)
    {
        $model = $this->get($model->id);

        if(!$model)
            return null;

        // vars
        $fieldsToUpdate = $data;
        $movieFormats = explode(',', $data['movie_formats']);

        // headquarter
        unset($fieldsToUpdate['movie_formats']);

        if(array_key_exists('password', $fieldsToUpdate))
            if(!is_null($fieldsToUpdate['password']))
                $fieldsToUpdate['password'] = encrypt($fieldsToUpdate['password']);

        $model->update($fieldsToUpdate);

        // headquarters movie format
        $model->movie_formats()->sync($movieFormats);

        return $model;
    }

    public function delete(Headquarter $model)
    {
        $model = $this->get($model->id);

        if($model)
            $model->delete();
    }

    public function listHeadquarters(Request $request)
    {
        $city_id = $request->get('city_id');
        $movie_format_id = $request->get('movie_format_id');
        $text = $request->get('text');

        $result = Headquarter::with('city', 'movie_formats', 'headquarter_images')
            ->active()
            ->when($city_id, function ($q) use ($city_id) {
                $q->where('city_id', $city_id);
            })
            ->when($movie_format_id, function ($q) use ($movie_format_id) {
                return $q->whereHas('movie_formats', function ($query) use ($movie_format_id) {
                    return $query->where('movie_formats.id', $movie_format_id);
                });
            })
            ->when($text, function ($q) use ($text) {
                $q->where('name', 'LIKE', '%'.$text.'%')
                    ->orWhere('address', 'LIKE', '%'.$text.'%');
            })
            ->get()
            ->sortByDesc(function ($h, $key) {
                return $h->is_favorite;
            });

        return $result->values()->all();
    }

    public function detailHeadquarter($headquarter)
    {
        return $headquarter->with('city', 'movie_formats', 'headquarter_images')->get()[0];
    }

    public function headquartersAvailableOfTheMovie(Movie $movie)
    {
        return Headquarter::with(['city', 'movie_formats'])
            ->whereHas('movie_times', function ($query) use ($movie) {
                return $query->where('movie_id', $movie->id);
            })
            ->get();
    }

    public function test(): JsonResponse 
    {
        $blogs = TestModel::all();
        return response()->json($blogs);
    }
}
