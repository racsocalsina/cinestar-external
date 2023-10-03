<?php


namespace App\Models\MovieTimeTariffs\Repositories;

use App\Enums\GlobalEnum;
use App\Models\MovieTariff\MovieTariff;
use App\Models\MovieTimes\MovieTime;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\MovieTimeTariffs\Repositories\Interfaces\MovieTimeTariffRepositoryInterface;

class MovieTimeTariffRepository implements MovieTimeTariffRepositoryInterface
{
    private $model;

    public function __construct(MovieTimeTariff $model)
    {
        $this->model = $model;
    }

    public function sync($body) : void
    {
        $action = $body['action'];
        $data = $body['data'];

        if (isset($body['url'])) {
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->first();
            $headquarter_id = $syncHeadquarter->id;
        }
        if (!isset($data))
            return;


        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            $mt = MovieTimeTariff::where('remote_id', $data['funkey'] . '-' . $data['funtar'])
            ->whereHas('movie_time', function ($query) use ($headquarter_id) {
                $query->where('headquarter_id', $headquarter_id)
                      ->latest('created_at');
            })
            ->first();
            
            if (isset($mt)) {
                $mt->update(['active' => false]);
                $mt->delete();
            }

            return;
        }

        // create movie_tarrifs if it does not exists
        $movieTariff = MovieTariff::where('remote_funtar', $data['funtar'])->visible()->first();

        if ($movieTariff == null) {
            if (!isset($data))
                return;

            $movieTariff = MovieTariff::create([
                'name'          => $data['name'],
                'remote_funtar' => $data['funtar'],
            ]);
        }

        // movie_time_tarrif
        $movieTime = MovieTime::where('remote_funkey', $data['funkey'])
        ->where('headquarter_id', $headquarter_id)
        ->latest('created_at')
        ->first();

        if(!isset($movieTime))
            return;

        $movieTimeTariff = MovieTimeTariff::withTrashed()
        ->where('remote_id', $data['id'])
        ->whereHas('movie_time', function ($query) use ($headquarter_id) {
            $query->where('headquarter_id', $headquarter_id)
                  ->latest('created_at');
        })->first();
        if (isset($movieTimeTariff) && isset($movieTimeTariff->id)) {
            MovieTimeTariff::withTrashed()->where('remote_id', $data['id'])
                ->whereHas('movie_time', function ($query) use ($headquarter_id) {
                    $query->where('headquarter_id', $headquarter_id)
                          ->latest('created_at');
                })->update([
                    'movie_time_id'   => $movieTime->id,
                    'movie_tariff_id' => $movieTariff->id,
                    'online_price'    => $data['online_price'],
                    'is_presale'      => $data['is_presales'],
                    'remote_id'       => $data['id'],
                    'deleted_at'      => null,
                    'active'          => true
                ]);
        } else {
            MovieTimeTariff::create([
                'movie_time_id'   => $movieTime->id,
                'movie_tariff_id' => $movieTariff->id,
                'online_price'    => $data['online_price'],
                'is_presale'      => $data['is_presales'],
                'remote_id'       => $data['id']
            ]);
        }
    }
}
