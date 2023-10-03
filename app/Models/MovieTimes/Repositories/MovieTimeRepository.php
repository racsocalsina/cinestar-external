<?php


namespace App\Models\MovieTimes\Repositories;

use App\Enums\GlobalEnum;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\MovieTariff\MovieTariff;
use App\Models\MovieTimes\MovieTime;
use App\Models\MovieTimes\Repositories\Interfaces\MovieTimeRepositoryInterface;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\MovieTimeTariffs\Repositories\Interfaces\MovieTimeTariffRepositoryInterface;
use App\Models\Rooms\Room;
use App\SearchableRules\MovieTimeSearchableRule;
use App\Services\Searchable\Searchable;

class MovieTimeRepository implements MovieTimeRepositoryInterface
{
    private $model;
    private $searchableService;
    private MovieTimeTariffRepositoryInterface $movieTimeTariffRepository;

    public function __construct(MovieTime $model, Searchable $searchableService, MovieTimeTariffRepositoryInterface $movieTimeTariffRepository)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
        $this->movieTimeTariffRepository = $movieTimeTariffRepository;
    }

    public function queryable()
    {
        return $this->model->where('active', 1);
    }

    public function listMovieTimes() {
        return MovieTime::with('headquarter');
    }

    public function updateGraph($data) {
        $movieTime = null;
        if (isset($data['url'])) {
            $syncHeadquarter = Headquarter::where('api_url', $data['url'])->first();
            $movieTime = MovieTime::where('remote_funkey', $data['funkey'])
                        ->where('headquarter_id', $syncHeadquarter->id)
                        ->latest('created_at')
                        ->first();
        }else{
            $movieTime = MovieTime::where('remote_funkey', $data['funkey'])->first();
        }
        $movieTime->update([
            'planner_graph' => $data['graph'],
            'planner_meta' => $data['planner_meta']
        ]);
    }

    public function searchMovieTimeOfHeadquarter(array $params, Headquarter $headquarter)
    {
        $query = $this->model
            ->with(['movie', 'room']);

        $this->searchableService->applyArray($query, new MovieTimeSearchableRule(), $params);

        return $query
            ->where('headquarter_id', $headquarter->id)
            ->orderBy('start_at', 'desc')
            ->orderBy('room_id', 'asc')
            ->paginate(Helper::perPage($params));
    }

    public function update(MovieTime $model, $body)
    {
        $model->save();
        return $model;
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        if($action == GlobalEnum::ACTION_SYNC_DELETE){
            $mt = MovieTime::where('remote_funkey', $data['funkey'])
            ->where('headquarter_id', $syncHeadquarter->id)
            ->latest('created_at')->first();

            if (isset($mt)) {
                $mt->update(['active' => false]);
                $mt->delete();
            }

            return;
        }

        $movie = Movie::where('code', $data['fun_pel'])->first();
        $room = Room::where('room_number', $data['nro_room'])
        ->where('headquarter_id', $syncHeadquarter->id)
        ->where('active', 1) 
        ->first();
  
        $movieTime = MovieTime::withTrashed()
        ->where('remote_funkey', $data['fun_key'])
        ->where('headquarter_id', $syncHeadquarter->id)
        ->latest('created_at')
        ->first();
        if(isset($movieTime) && isset($movieTime->id) && isset($movie)){
            $movieTime = MovieTime::withTrashed()
                ->where('remote_funkey', $data['fun_key'])
                ->where('headquarter_id', $syncHeadquarter->id)
                ->latest('created_at')
                ->firstOrFail();

            $movieTime->update([
                'room_id'          => $room->id,
                'movie_id'         => $movie->id,
                'headquarter_id'   => $syncHeadquarter->id,
                'remote_funkey'    => $data['fun_key'],
                'fun_nro'          => $data['fun_nro'],
                'start_at'         => $data['start_at'],
                'date_start'       => $data['start_date'],
                'time_start'       => $data['start_time'],
                'is_presale'       => $data['is_presales'],
                'planner_graph'    => $data['graph']['graph'],
                'planner_meta'     => $data['graph']['planner_meta'],
                'is_numerated'     => $data['is_numerated'],
                'deleted_at'       => null,
                'active'           => true
            ]);
        }else{

            if(!isset($room->id) || !isset($movie->id)){
                return;
            }

            $movieTime = MovieTime::create([
                'room_id'          => $room->id,
                'movie_id'         => $movie->id,
                'headquarter_id'   => $syncHeadquarter->id,
                'remote_funkey'    => $data['fun_key'],
                'fun_nro'          => $data['fun_nro'],
                'start_at'         => $data['start_at'],
                'date_start'       => $data['start_date'],
                'time_start'       => $data['start_time'],
                'is_presale'       => $data['is_presales'],
                'planner_graph'    => $data['graph']['graph'],
                'planner_meta'     => $data['graph']['planner_meta'],
                'is_numerated'     => $data['is_numerated']
            ]);

            // always create new movie_time_tarrif of type Z for promotions (this records is only for backend internal valdations)
            $movieTariff = MovieTariff::where('remote_funtar', 'Z')->first();
            if($movieTariff){
                MovieTimeTariff::create([
                    'movie_time_id'   => $movieTime->id,
                    'movie_tariff_id' => $movieTariff->id,
                    'online_price'    => 0,
                    'is_presale'      => 0,
                    'remote_id'       => 0
                ]);
            }
        }

        if($action == GlobalEnum::ACTION_SYNC_IMPORT)
        {
            $prices = $data['prices'];
            foreach ($prices as $value) {
                $body = [
                    'action' => $action,
                    'url' => $body['url'],
                    'data' => $value
                ];
                $this->movieTimeTariffRepository->sync($body);
            }
        }
    }

    public function getSchedules($params)
    {
        $headquarter_id = $params['headquarter_id'];
        $movie_id = $params['movie_id'];
        return $this->model->select('time_start')
            ->when($headquarter_id, function ($q) use ($headquarter_id) {
                $q->where('headquarter_id', $headquarter_id);
            })
            ->when($movie_id, function ($q) use ($movie_id) {
                $q->where('movie_id', $movie_id);
            })
            ->orderBy('time_start')
            ->groupBy('time_start')
            ->get();
    }
}
