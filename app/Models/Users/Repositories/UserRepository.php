<?php

namespace App\Models\Users\Repositories;

use App\Enums\GlobalEnum;
use App\Enums\PointHistoryTypes;
use App\Enums\PurchaseStatus;
use App\Enums\SalesType;
use App\Enums\TradeName;
use App\Helpers\FileHelper;
use App\Helpers\Helper;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\Customers\Customer;
use App\Models\PointsHistory\PointHistory;
use App\Models\Purchases\Purchase;
use App\Models\TicketAwards\TicketAward;
use App\Models\UsersPartners\Repositories\Interfaces\UserPartnerRepositoryInterface;
use App\User;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\UsersPartners\UserPartner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    private UserPartnerRepositoryInterface $userPartnerRepository;

    public function __construct(UserPartnerRepositoryInterface $userPartnerRepository)
    {
        $this->userPartnerRepository = $userPartnerRepository;
    }

    public function createCustomer($data)
    {
        $user = User::create([
            'username' => $data->document_number,
            'password' => bcrypt($data->password),
            'resetPassword' => 0,
            'trade_name' => $data->has('trade_name') ? $data->trade_name : Helper::getTradeNameHeader(),
        ]);
        $trace = $user->trade_name == TradeName::CINESTAR ? 1 : 2;
        $socio_cod = $trace . substr($user->username, 0, 12);
        $customer = Customer::create([
            'name' => $data->name,
            'lastname' => $data->lastname,
            'email' => $data->email,
            'document_type' => $data->document_type,
            'document_number' => $data->document_number,
            'cellphone' => $data->cellphone,
            'birthdate' => $data->birthdate,
            'user_id' => $user->id,
            'department_id' => $data->department_id,
            'trade_name' => $data->has('trade_name') ? $data->trade_name : Helper::getTradeNameHeader(),
            'socio_cod' => $data->has('socio_cod') ? $data->socio_cod : $socio_cod,
            'status' => 'A',
            'registration_date' => $user->created_at->format('Y-m-d')
        ]);
        return [$user, $customer];
    }

    public function changePassword($data)
    {
        if ($data->origin === 'web') {
            $customer = Customer::where('email', $data->email)->first();
        } else {
            $customer = Customer::where('document_number', $data->nro_document)->first();
        }
        $user = User::find($customer->user_id);
        $user->password = bcrypt($data->password);
        $user->save();
    }

    public function getDataProfile($user)
    {
        $customer = Customer::where('user_id', $user->id)->with('department')->first();
        $collectionCustomer = collect($customer);

        $soccod = $customer->socio_cod;
        $userPartner = UserPartner::where('soccod', $soccod)->first();
        $collectionPromoPoints = [];
        $ticketAwards = TicketAward::orderBy('points')->get();
        $chocoAwards = ChocoAward::orderBy('points')->get();

        if ($userPartner) {
            $pointsHistory = PointHistory::where('soccod', $soccod)
                ->excludeOldRecords()
                ->orderBy('id', 'desc')
                ->get();

            $collectionPromoPoints = collect([
                'ticket_promotional_data' => [
                    'points' => $userPartner->ticketPoints,
                    'movements' => $this->getMovementsBySalesType($pointsHistory, SalesType::TICKET),
                    'awards' => $ticketAwards
                ],
                'choco_promotional_data' => [
                    'points' => $userPartner->chocoPoints,
                    'movements' => $this->getMovementsBySalesType($pointsHistory, SalesType::SWEET),
                    'awards' => $chocoAwards
                ]
            ]);
        }

        return $collectionCustomer->merge($collectionPromoPoints);
    }

    private function getMovementsBySalesType($pointsHistory, $salesType)
    {
        $movements = $pointsHistory->where('sales_type', $salesType)->groupBy(function ($item, $key) {
            return $item["type"] . "-" . $item["remote_movkey"];
        });

        $arrayMovements = [];

        $movements->map(function ($data) use (&$arrayMovements) {
            $firstItem = $data[0];

            array_push($arrayMovements, [
                'increase' => $firstItem['type'] == PointHistoryTypes::GANADO,
                'points' => $data->sum('points'),
                'description' => $this->getPointDescriptionByType($firstItem),
                'expiration' => $this->getPointExpirationByType($firstItem)
            ]);
        });

        return $arrayMovements;
    }

    private function getPointDescriptionByType($item)
    {
        if ($item['type'] == PointHistoryTypes::GANADO)
            return "Adquiridos el {$item['created_at']->format('d/m/Y')}";

        if ($item['type'] == PointHistoryTypes::EXPIRADO) {
            $date = isset($item['expiration_date']) ? $item['expiration_date']->format('d/m/Y') : null;
            return "Puntos expirados de la fecha {$date}";
        }

        if ($item['type'] == PointHistoryTypes::CANJEADO)
            return "Canjeados el {$item['created_at']->format('d/m/Y')}";

        return null;
    }

    private function getPointExpirationByType($item)
    {
        if (isset($item['expiration_date']) && $item['type'] == PointHistoryTypes::GANADO)
            return "Vence {$item['expiration_date']->format('d/m/Y')}";

        return null;
    }

    public function editProfile($user, Request $request)
    {
        $idCustomer = Customer::where('user_id', $user->id)->first();

        if ($idCustomer->document_number !== $request->document_number) {
            $userUpdate = User::find($user->id);
            $userUpdate->username = $request->document_number;
            $userUpdate->save();
        }
        $customer = Customer::find($idCustomer->id);

        $soc_cod = $customer->socio_cod;
        $trace = $customer->trade_name == TradeName::CINESTAR ? 1 : 2;
        $new_soc_cod = $trace . substr($request->document_number, 0, 12);
        $customer->document_type = $request->document_type;
        $customer->document_number = $request->document_number;
        $customer->cellphone = $request->cellphone;
        $customer->name = $request->name;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->socio_cod = $new_soc_cod;
        if ($request->department_id) {
            $customer->department_id = $request->department_id;
        }
        $customer->save();

        if ($soc_cod) {
            $socio = UserPartner::where('soccod', $soc_cod)->first();
            $socio->update([
                'soccod' => $new_soc_cod,
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


        return [
            'user_id' => $user->id,
            'email' => $customer->email,
            'cellphone' => $customer->cellphone,
            'image' => $customer->image_path,
            'name' => $customer->name,
            'lastname' => $customer->lastname,
            'birthdate' => $customer->birthdate,
            'document_type' => $customer->document_type,
            'document_number' => $customer->document_number
        ];
    }

    public function editImageProfile($user, Request $request)
    {
        $customer = Customer::where('user_id', $user->id)->first();
        $customerFind = Customer::find($customer->id);
        $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::CUSTOMERS_FOLDER, $request->file('image'));
        $customerFind->image_path = $file_name;
        $customerFind->save();
        $data = [
            'user_id' => $user->id,
            'email' => $customerFind->email,
            'image' => $customerFind->image_path,
            'cellphone' => $customerFind->cellphone,
            'name' => $customerFind->name,
            'lastname' => $customerFind->lastname,
            'birthdate' => $customerFind->birthdate,
            'document_type' => $customerFind->document_type,
            'document_number' => $customerFind->document_number
        ];
        return $data;
    }

    public function userIsRecurringBuyer(User $user)
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');
        $limit = 2;

        $ret = Purchase::where('user_id', $user->id)
                ->where('confirmed', true)
                ->whereDate('created_at', '>=', $weekStartDate)
                ->whereDate('created_at', '<=', $weekEndDate)
                ->get()
                ->count() >= $limit;

        return ($ret ? 1 : 0);
    }

    public function getRegisterUserDays($user)
    {
        return Carbon::now()->diffInDays($user->created_at) + 1;
    }

    public function getAntifraudData()
    {
        return [
            'MDD21' => null, //$this->userIsRecurringBuyer($user),
            'MDD75' => null, //'Registrado',
            'MDD77' => null, //$this->getRegisterUserDays($user),
        ];
    }
}
