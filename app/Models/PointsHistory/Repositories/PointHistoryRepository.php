<?php


namespace App\Models\PointsHistory\Repositories;

use App\Enums\AmountByPoint;
use App\Enums\PointHistoryTypes;
use App\Enums\SalesType;
use App\Models\ChocoAwards\ChocoAward;
use App\Models\Customers\Customer;
use App\Models\Movies\Repositories\Interfaces\MovieValidPromotionRepositoryInterface;
use App\Models\PointsHistory\PointHistory;
use App\Models\PointsHistory\Repositories\Interfaces\PointHistoryRepositoryInterface;
use App\Models\Purchases\Purchase;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\TicketAwards\TicketAward;
use App\Models\UsersPartners\UserPartner;
use App\User;
use Carbon\Carbon;

class PointHistoryRepository implements PointHistoryRepositoryInterface
{
    private $repository;

    public function __construct(PointHistory $repository)
    {
        $this->repository = $repository;
    }


    public function store(Purchase $purchase)
    {
        $settingRepository = \App::make(SettingRepositoryInterface::class);

        $config = $settingRepository->getSystemConfiguration();
        $accumulatePoints = true;
        if($config)
            if(isset($config['accumulate_points']))
                $accumulatePoints = boolval($config['accumulate_points']);

        /**@var User $user * */
        $user = $purchase->user;
        $arrayPoints = [
            'ticket_incremental_points' => 0,
            'ticket_decremental_points' => 0,
            'choco_incremental_points' => 0,
            'choco_decremental_points' => 0,
        ];

        // Increment process
        if($accumulatePoints) {
            if ($purchase->purchase_items->whereNotNull('purchase_ticket_id')->count()) {
                $tickets = $purchase->purchase_items->whereNotNull('purchase_ticket_id')->pluck('ticket');
                if ($tickets->whereNull('purchase_promotion_id')->count()) {
                    $amount = $tickets->whereNull('purchase_promotion_id')->sum('purchaseItem.original_amount');
                    $points = intval($amount / AmountByPoint::AMOUNT);
                    $this->insertIncrementalPoints($user, $points, SalesType::TICKET, $purchase);
                    $arrayPoints['ticket_incremental_points'] = $points;
                }
            }

            if ($purchase->purchase_items->whereNotNull('purchase_sweet_id')->count()) {
                $sweet = $purchase->purchase_items->whereNotNull('purchase_sweet_id')->pluck('sweet');
                if ($sweet->whereNull('purchase_promotion_id')->count()) {
                    $amount = $sweet->whereNull('purchase_promotion_id')->sum('purchaseItem.original_amount');
                    $points = intval($amount / AmountByPoint::AMOUNT);
                    $this->insertIncrementalPoints($user, $points, SalesType::SWEET, $purchase);
                    $arrayPoints['choco_incremental_points'] = $points;
                }
            }
        }

        // Decrement process
        $ticket_awards = $purchase->promotions->where('replace_type', TicketAward::class);
        $choco_awards = $purchase->promotions->where('replace_type', ChocoAward::class);

        if ($ticket_awards->count()) {
            $arrayPoints['ticket_decremental_points'] = $this->insertDecrementalPoints($purchase, $ticket_awards, SalesType::TICKET);
        }
        if ($choco_awards->count()) {
            $arrayPoints['choco_decremental_points'] = $this->insertDecrementalPoints($purchase, $choco_awards, SalesType::SWEET);
        }

        // Finally Process
        $this->updatePoints($purchase, $arrayPoints);
    }

    private function updatePoints($purchase, $points)
    {
        $user = $purchase->user;

        // save total points earned in purchases table
        $purchase->purchase_ticket()->update([
            'points' => $points['ticket_incremental_points']
        ]);

        $purchase->purchase_sweet()->update([
            'points' => $points['choco_incremental_points']
        ]);

        // save on socios table for ticket
        $ticketPoints = $user->customer->user_partner_cod->ticketPoints;
        $ticketHistoryPoints = $user->customer->user_partner_cod->ticketHistoryPoints;
        $chocoPoints = $user->customer->user_partner_cod->chocoPoints;
        $chocoHistoryPoints = $user->customer->user_partner_cod->chocoHistoryPoints;

        if($points['ticket_incremental_points'] > 0)
        {
            $ticketPoints += $points['ticket_incremental_points'];
            $ticketHistoryPoints += $points['ticket_incremental_points'];
        }

        if($points['ticket_decremental_points'] > 0)
            $ticketPoints -= $points['ticket_decremental_points'];

        if($points['choco_incremental_points'] > 0)
        {
            $chocoPoints += $points['choco_incremental_points'];
            $chocoHistoryPoints += $points['choco_incremental_points'];
        }

        if($points['choco_decremental_points'] > 0)
            $chocoPoints -= $points['choco_decremental_points'];

        // save
        $user->customer->user_partner_cod()->update([
            'socpun' => $ticketPoints,
            'socacu' => $ticketHistoryPoints,
            'so2pun' => $chocoPoints,
            'so2acu' => $chocoHistoryPoints
        ]);
    }

    private function insertIncrementalPoints($user, $points, $type, $purchase)
    {
        $settingRepository = \App::make(SettingRepositoryInterface::class);
        $setting = $settingRepository->getCommunitySystemVars($purchase->headquarter_id);
        $days = $setting['config']['predia'];

        $remote_movkey = null;

        if ($type == SalesType::TICKET)
            $remote_movkey = $purchase->purchase_ticket ? $purchase->purchase_ticket->remote_movkey : null;
        else if ($type == SalesType::SWEET)
            $remote_movkey = $purchase->purchase_sweet ? $purchase->purchase_sweet->remote_movkey : null;

        // add records to points_history table
        for ($i = 0; $i < $points; $i++) {
            $this->repository->create([
                'soccod' => $user->customer->user_partner_cod->soccod,
                'type' => PointHistoryTypes::GANADO,
                'points' => 1,
                'created_at' => now(),
                'expiration_date' => Carbon::now()->addDay($days)->toDateTimeString(),
                'from_erp' => 0,
                'sales_type' => $type,
                'remote_movkey' => $remote_movkey,
                'available' => true,
            ]);
        }

    }

    private function insertDecrementalPoints($purchase, $promotions, $type): int
    {
        /**@var User $user * */
        $user = $purchase->user;
        $points_use = 0;
        foreach ($promotions as $promotion) {
            if ($promotion->replacement instanceof TicketAward) {
                $points = $promotion->replacement->points * $promotion->qty;
                $remote_movkey = $purchase->purchase_ticket->remote_movkey;
            } else {
                $points = $promotion->replacement->points * $promotion->qty;
                $remote_movkey = $purchase->purchase_sweet->remote_movkey;
            }
            $this->repository->create([
                'soccod' => $user->customer->user_partner_cod->soccod,
                'type' => PointHistoryTypes::CANJEADO,
                'points' => $points,
                'from_erp' => 0,
                'sales_type' => $type,
                'purchase_promotion_id' => $promotion->id,
                'remote_movkey' => $remote_movkey,
                'available' => false
            ]);
            $points_use += $points;
        }

        return $points_use;
    }

    public function getExpiredPoints()
    {
        return PointHistory::whereDate('expiration_date', '<=', now()->format('Y-m-d'))
            ->where('type', PointHistoryTypes::GANADO)
            ->where('available', true)
            ->get();
    }

    public function addExpirationPoint($pointHistoryRelated): void
    {
        PointHistory::create([
            'soccod' => $pointHistoryRelated->soccod,
            'type' => PointHistoryTypes::EXPIRADO,
            'points' => $pointHistoryRelated->points,
            'created_at' => now(),
            'expiration_date' => $pointHistoryRelated->expiration_date,
            'from_erp' => $pointHistoryRelated->from_erp,
            'sales_type' => $pointHistoryRelated->sales_type,
            'purchase_promotion_id' => $pointHistoryRelated->purchase_promotion_id,
            'remote_movkey' => $pointHistoryRelated->remote_movkey,
            'available' => false,
        ]);

        $this->updateExpiredPointHistoryAsNotAvailable($pointHistoryRelated);
        $this->decrementPointByExpiredPointHistory($pointHistoryRelated);
    }

    public function getCheckPointsData($user, $movieTimeId)
    {
        $movieValidPromotionRepository = \App::make(MovieValidPromotionRepositoryInterface::class);
        $settingRepository = \App::make(SettingRepositoryInterface::class);

        $config = $settingRepository->getSystemConfiguration();
        $maxMinutesToBuy = 5;
        if($config)
            if(isset($config['max_minutes_to_buy']))
                $maxMinutesToBuy = intval($config['max_minutes_to_buy']);

        $customer = null;
        $soccod = null;
        $userPartner = null;
        $ticketAllowedForPromotions = true;

        if($user)
        {
            $customer = Customer::where('user_id', $user->id)->first();
            $soccod = $customer->socio_cod;
            $userPartner = UserPartner::where('soccod', $soccod)->first();
        }

        if($movieTimeId)
        {
            $ticketAllowedForPromotions = $movieValidPromotionRepository->checkMovieIsValidForPromotions($movieTimeId);
        }
        return [
            'ticket' => [
                'total_points' => $userPartner ? $userPartner->ticketPoints : 0,
                'points_to_expire' => $customer ? $this->getTotalPointsToExpire($soccod, SalesType::TICKET) : 0,
                'allowed_for_promotions' => $ticketAllowedForPromotions
            ],
            'choco' => [
                'total_points' => $userPartner ? $userPartner->chocoPoints : 0,
                'points_to_expire' => $customer ? $this->getTotalPointsToExpire($soccod, SalesType::SWEET) : 0,
                'allowed_for_promotions' => true
            ],
            'max_minutes_to_buy' => $maxMinutesToBuy
        ];
    }

    private function getTotalPointsToExpire($soccod, $salesType) :int
    {
        $daysToExpire = 30;
        $nowDate = now()->toDateString();

        $total = PointHistory::where('soccod', $soccod)
            ->where('type', PointHistoryTypes::GANADO)
            ->where('available', true)
            ->whereDate('expiration_date', '>=', $nowDate)
            ->whereNotNull('expiration_date')
            ->whereRaw("DATEDIFF(date(expiration_date), '{$nowDate}') between 0 and {$daysToExpire}")
            ->where('sales_type', $salesType)
            ->sum('points');

        return is_null($total) ? 0 : intval($total);
    }

    private function updateExpiredPointHistoryAsNotAvailable($pointHistory)
    {
        $pointHistory->available = false;
        $pointHistory->save();
    }

    private function decrementPointByExpiredPointHistory($pointHistory)
    {
        $userPartner = UserPartner::where('soccod', $pointHistory->soccod)->first();

        if (!$userPartner)
            return;

        if ($pointHistory->sales_type == SalesType::TICKET)
            $userPartner->socpun = $userPartner->socpun - $pointHistory->points;
        else
            $userPartner->so2pun = $userPartner->so2pun - $pointHistory->points;

        $userPartner->save();
    }
}
