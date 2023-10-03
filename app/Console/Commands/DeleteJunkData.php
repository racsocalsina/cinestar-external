<?php


namespace App\Console\Commands;


use App\Enums\PurchaseStatus;
use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DeleteJunkData extends Command
{
    protected $signature = 'system:delete-junk-data';
    protected $description = 'Delete temporary, old or junk data that consumes disk space';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->deleteOldPendingPurchases();
            $this->deleteOldPaymentGatewayInfoThatWereDeleted();
            $this->deleteTempFiles();
        } catch (\Exception $exception) {
            $message = "Error en el comando DeleteJunkData";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        }
    }

    private function deleteOldPendingPurchases()
    {
        DB::delete("
            delete from purchases
            where
                not deleted_at is null
                and deleted_at < (NOW() - INTERVAL :days day)",
            [
                'days' => $this->getDays()
            ]
        );

        DB::delete("
            delete from purchases
            where
                (status = :status1 or status = :status2)
                and created_at < (NOW() - INTERVAL :days day)",
            [
                'status1' => PurchaseStatus::PENDING,
                'status2' => PurchaseStatus::ERROR_PAYMENT_GATEWAY,
                'days' => $this->getDays()
            ]
        );
    }

    private function deleteOldPaymentGatewayInfoThatWereDeleted()
    {
        DB::delete("
            delete from payment_gateway_info
            where
                not deleted_at is null
                and deleted_at < (NOW() - INTERVAL :days day)",
            ['days' => $this->getDays()]
        );
    }

    private function getDays()
    {
        return FunctionHelper::getValueSystemConfigurationByKey('system_days_to_delete');
    }

    private function deleteTempFiles()
    {
        File::cleanDirectory(storage_path("app/temp"));
    }
}

