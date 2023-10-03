<?php

namespace App\Console\Commands;

use App\Enums\TradeName;
use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\Customers\Customer;
use App\Models\JobTrigger\JobTrigger;
use App\Services\Mail\Actions\BuildExceptionDto;
use App\User;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\UsersPartners\UserPartner;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ChangeCustomer extends Command
{
    private $userRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecutar job triggers de customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }


    public function handle()
    {
        try {
            DB::beginTransaction();
            $jobs = JobTrigger::where('status', 'PENDENT')->where('origin', 'qmaesoc')->get();
            $status = 'EXECUTED';
            $description = 'OK';

            foreach ($jobs as $job)
            {
                $userPartners = UserPartner::withoutGlobalScopes()->where([
                    'soccod' => $job->origin_id
                ])->first();

                if ($userPartners && $job->type == 'INSERT') {

                    $customer = Customer::withoutGlobalScopes()->where('socio_cod', $job->origin_id)->first();

                    if($customer)
                        $description = 'CLIENTE YA EXISTE, ESTE PASO SE OMITIRA';
                    else
                        $this->insert($userPartners, $job);

                } else if ($userPartners && $job->type == 'UPDATE') {
                    [$status, $description] = $this->update($userPartners);

                } else if ($userPartners && $job->type == 'DELETE') {
                    //$this->delete($userPartners);
                    //$status = 'PENDENT';
                    $description = 'CLIENTE NO ELIMINADO, SI DESEA ELIMINARLO REALIZARLO MANUALMENTE';
                }

                $job->update([
                    'status' => $status,
                    'executed_date' => Carbon::now()->toDateTimeString(),
                    'description' => $description
                ]);
            }

            DB::commit();
            $this->info("OK_" . Carbon::now());
            return;
        } catch (Exception $exception) {
            DB::rollBack();
            $message = "Error en el comando change:customer";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            $this->info($exception->getMessage() . '_' . Carbon::now());
            return;
        }
    }

    public function insert($userPartners, $job)
    {
        $request = new Request();
        if (!User::withoutGlobalScopes()->where('username', $userPartners->socdni)->first()) {

            $tradeName = substr($job->origin_id, 0, 1) == 1 ? TradeName::CINESTAR : TradeName::MOVIETIME;

            $request->replace([
                'email' => $userPartners->socema,
                'name' => $userPartners->socnom,
                'lastname' => $userPartners->socno2,
                'document_type' => $userPartners->soctdd,
                'document_number' => $userPartners->socdni,
                'cellphone' => $userPartners->soctel,
                'birthdate' => $userPartners->socnac,
                'password' => $userPartners->socdni,
                'password_confirmation' => $userPartners->socdni,
                'socio_cod' => $job->origin_id,
                'status' => $userPartners->socsta,
                'registration_date' => $userPartners->socing,
                'trade_name' => $tradeName
            ]);
            $this->userRepository->createCustomer($request);
        }
    }

    public function update($userPartners)
    {
        $tradeName = substr($userPartners->soccod, 0, 1) == 1 ? TradeName::CINESTAR : TradeName::MOVIETIME;
        $customer = Customer::withoutGlobalScopes()->where('socio_cod', $userPartners->soccod)->first();

        $customers = Customer::withoutGlobalScopes()
            ->where('socio_cod', '<>', $userPartners->soccod)
            ->where('trade_name', $tradeName)
            ->where(function($query) use ($userPartners) {
                $query->where('document_number', $userPartners->socdni)
                    ->orwhere('email', $userPartners->socema)
                    ->orwhere('cellphone', $userPartners->soctel);
            })->get();

        $user = User::withoutGlobalScopes()->whereId($customer->user_id)->first();

        if ($customers->count()) {
            return [
                'ERROR',
                'NUMERO DE DOCUMENTO O EMAIL O CELULAR YA EXISTEN'
            ];
        }

        if ($customer) {

            $tradeName = substr($userPartners->soccod, 0, 1) == 1 ? TradeName::CINESTAR : TradeName::MOVIETIME;

            $customer->update([
                'email' => $userPartners->socema,
                'name' => $userPartners->socnom,
                'lastname' => $userPartners->socno2,
                'document_type' => $userPartners->soctdd,
                'document_number' => $userPartners->socdni,
                'cellphone' => $userPartners->soctel,
                'birthdate' => $userPartners->socnac,
                'status' => $userPartners->socsta,
                'registration_date' => $userPartners->socing,
                'trade_name' => $tradeName
            ]);

            $user->update([
                'username' => $userPartners->socdni,
                'trade_name' => $tradeName,
            ]);
        }
        return ['EXECUTED', 'OK'];

    }
}
