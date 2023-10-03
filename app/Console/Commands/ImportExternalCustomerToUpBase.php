<?php

namespace App\Console\Commands;

use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\Customers\Customer;
use App\Models\UsersPartners\Repositories\Interfaces\UserPartnerRepositoryInterface;
use App\Models\UsersPartners\UserPartner;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ImportExternalCustomerToUpBase extends Command
{
    protected $signature = 'import:external-customer';
    protected $description = 'Command description';
    private $userPartnerRepository;

    public function __construct(UserPartnerRepositoryInterface $userPartnerRepository)
    {
        parent::__construct();
        $this->userPartnerRepository = $userPartnerRepository;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();

            Customer::withoutGlobalScopes()->chunk(1000, function($results) {

                foreach ($results as $data)
                {
                    // update socio_cod
                    $soccod = FunctionHelper::generateSocCod($data->trade_name, $data->document_number);
                    $data->socio_cod = $soccod;
                    $data->save();

                    // insert new record to socios tables
                    $socio = UserPartner::where('soccod', $data->socio_cod)->first();
                    if (!$socio) {
                        $this->userPartnerRepository->createFromExternal($data);
                    } else {

                        $customer = Customer::where('socio_cod', $data->socio_cod)->first();

                        if($customer)
                        {
                            $socio->update([
                                'soccod' => $customer->socio_cod,
                                'socnom' => $customer->name,
                                'socno2' => $customer->lastname,
                                'soctel' => $customer->cellphone,
                                'socema' => $customer->email,
                                'socdni' => $customer->document_number,
                                'socnac' => $customer->birthdate,
                                'soctdd' => $customer->document_type,
                                'socsta' => $customer->status,
                                'socing' => $customer->registration_date,
                            ]);
                        }
                    }
                }
            });

            DB::commit();
            $this->info("OK_" . Carbon::now());
            return;
        } catch (Exception $exception) {
            DB::rollBack();
            $message = "Error en el comando import:external-customer";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            $this->info($exception->getMessage() . '_' . Carbon::now());
            return;
        }
    }
}
