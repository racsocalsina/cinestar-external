<?php


namespace App\Console\Commands;


use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\PointsHistory\Repositories\Interfaces\PointHistoryRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Console\Command;

class PointExpirationProcess extends Command
{
    protected $signature = 'system:point-expiration-process';
    protected $description = 'calculates the points that are about to expire and updates them';

    private PointHistoryRepositoryInterface $pointHistoryRepository;

    public function __construct(PointHistoryRepositoryInterface $pointHistoryRepository)
    {
        parent::__construct();
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    public function handle()
    {
        try {

            $dataList = $this->pointHistoryRepository->getExpiredPoints();

            foreach ($dataList as $pointHistory)
            {
                $this->pointHistoryRepository->addExpirationPoint($pointHistory);
            }

        } catch (\Exception $exception) {
            $message = "Error en el comando PointExpirationProcess";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        }
    }
}

