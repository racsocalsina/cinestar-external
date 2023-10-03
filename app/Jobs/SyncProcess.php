<?php


namespace App\Jobs;

use App\Enums\GlobalEnum;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Jobs\Import\ChocoAwardImport;
use App\Jobs\Import\ChocoPromotionImport;
use App\Jobs\Import\MovieImport;
use App\Jobs\Import\MovieTimeImport;
use App\Jobs\Import\ProductImport;
use App\Jobs\Import\ProductPriceImport;
use App\Jobs\Import\ProductTypeImport;
use App\Jobs\Import\RoomImport;
use App\Jobs\Import\SettingImport;
use App\Jobs\Import\TicketAwardImport;
use App\Jobs\Import\TicketPromotionImport;
use App\Jobs\mongo\SyncMovies;
use App\Models\Headquarters\Headquarter;
use App\Models\MongoMovies\Repositories\Interfaces\MongoMovieRepositoryInterface;
use App\Models\Movies\Repositories\Interfaces\MovieRepositoryInterface;
use App\Models\SyncLogs\Repositories\Interfaces\SyncLogRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $headquarter;

    public $timeout = 300;
    private MovieRepositoryInterface $movieRepository;
    private MongoMovieRepositoryInterface $mongomovieRepository;

    public function __construct(
        Headquarter $headquarter,
        MovieRepositoryInterface $movieRepository,
        MongoMovieRepositoryInterface $mongomovieRepository
    )
    {
        $this->headquarter = $headquarter;
        $this->movieRepository = $movieRepository;
        $this->mongomovieRepository = $mongomovieRepository;
    }


    public function handle(
        SyncLogRepositoryInterface $syncLogRepository,
        ProductTypeImport $productTypeImport,
        ProductImport $productImport,
        SettingImport $settingImport,
        ChocoPromotionImport $chocoPromotionImport,
        TicketPromotionImport $ticketPromotionImport,
        ProductPriceImport $productPriceImport,
        MovieImport $movieImport,
        RoomImport $roomImport,
        MovieTimeImport $movieTimeImport,
        ChocoAwardImport $chocoAwardImport,
        TicketAwardImport $ticketAwardImport
    )
    {
        ini_set('memory_limit', '-1');

        try {

            DB::beginTransaction();

            $token = Helper::loginInternal($this->headquarter);

            $settingImport->execute($token, $this->headquarter);
            $movieImport->execute($token, $this->headquarter);
            $roomImport->execute($token, $this->headquarter);
            $movieTimeImport->execute($token, $this->headquarter);
            $productTypeImport->execute($token, $this->headquarter);
            $productImport->execute($token, $this->headquarter);
            $productPriceImport->execute($token, $this->headquarter);
            $ticketPromotionImport->execute($token, $this->headquarter);
            $chocoPromotionImport->execute($token, $this->headquarter);
            $chocoAwardImport->execute($token, $this->headquarter);
            $ticketAwardImport->execute($token, $this->headquarter);

            $syncLogRepository->update($this->headquarter, GlobalEnum::SYNC_LOG_STATUS_SUCCESS);

            DB::commit();
            SyncMovies::dispatch($this->movieRepository, $this->mongomovieRepository, $this->headquarter->trade_name, "Manual")
                ->onQueue('SYNC_MONGO');
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();

            $syncLogRepository->update($this->headquarter, GlobalEnum::SYNC_LOG_STATUS_ERROR);

            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject('Error en el proceso de sincronización');
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        }
    }
}
