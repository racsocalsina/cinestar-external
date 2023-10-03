<?php

namespace App\Jobs\mongo;

use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MovieRepositoryInterface $movieRepository;
    private MongoMovieRepositoryInterface $mongomovieRepository;
    private $trade_name;
    private $info;
    private $code;

    public function __construct(MovieRepositoryInterface $movieRepository,
                                MongoMovieRepositoryInterface $mongomovieRepository,
                                $trade_name = null, $info, $code = null)
    {
        $this->movieRepository = $movieRepository;
        $this->mongomovieRepository = $mongomovieRepository;
        $this->trade_name = $trade_name;
        $this->info = $info;
        $this->code = $code;
    }

    public function handle()
    {
        Log::info("activate process: ".$this->info);

        try {
            $result = $this->movieRepository->listMoviesSyncMongo($this->trade_name, $this->code);
            $destinationArray = [];

            foreach ($result as $data) {
                $destinationArray[] = $data->result;
            }

            if ($this->code != null) {
                Log::info("result:");
                Log::info(json_decode($result));
            }

            $result_save = $this->mongomovieRepository->savemovies($destinationArray, $this->trade_name);
            if($result_save)
            {
                Log::info("update mongodb");
            }
            else
            {
                Log::info("failed mongodb");
            }
        } catch (Exception $exception) {
            Log::error('SyncMovies Mongo Queue failed');
            Log::error(json_decode($exception));
        }

    }
}
