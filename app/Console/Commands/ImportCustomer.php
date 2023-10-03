<?php

namespace App\Console\Commands;

use App\Enums\TradeName;
use App\Helpers\FunctionHelper;
use App\Jobs\SendErrorEmail;
use App\Models\Customers\Customer;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\UsersPartners\UserPartner;
use App\Services\Mail\Actions\BuildExceptionDto;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ImportCustomer extends Command
{
    private $userRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
                $data = UserPartner::distinct('socdni')->offset(220000)->limit(10000)->orderby('soccod', 'asc')->get();
            foreach ($data as $item) {
                $exist = Customer::where('socio_cod', $item->soccod)->first();
                if (!$exist) {
                    $customers = Customer::where('document_number', $item->socdni)->get();
                    if (!$customers->count()) {
                        $trade = substr($item->soccod, 0, 1) == 1 ? TradeName::CINESTAR : TradeName::MOVIETIME;
                        $request = new Request();
                        $request->replace([
                            'email' => $item->socema,
                            'name' => $item->socnom,
                            'lastname' => $item->socno2,
                            'document_type' => $item->soctdd,
                            'document_number' => $item->socdni,
                            'cellphone' => $item->soctel,
                            'birthdate' => $item->socnac != '0000-00-00' ? $item->socnac : null,
                            'password' => $item->socdni,
                            'password_confirmation' => $item->socdni,
                            'trade_name' => $trade,
                            'socio_cod' => $item->soccod,
                            'status' => $item->socsta,
                            'registration_date' => $item->socing,
                        ]);

                        $this->userRepository->createCustomer($request);
                    }
                }else{
                    $exist->trade_name = substr($exist->socio_cod, 0, 1) == 1 ? TradeName::CINESTAR : TradeName::MOVIETIME;
                    $exist->update();
                }

            }

            DB::commit();
            return "OK_" . Carbon::now();
        } catch (Exception $exception) {
            DB::rollBack();
            $message = "Error en el comando import:customer";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            return $exception->getMessage() . '_' . Carbon::now();
        }
    }
}
