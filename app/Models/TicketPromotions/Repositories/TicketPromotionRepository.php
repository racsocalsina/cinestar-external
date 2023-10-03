<?php


namespace App\Models\TicketPromotions\Repositories;


use App\Enums\GlobalEnum;
use App\Enums\MovieChainEnum;
use App\Enums\TicketPromotionType;
use App\Enums\TariffType;
use App\Enums\TicketPromotionForAllTariffs;
use App\Enums\TradeName;
use App\Helpers\FileHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTariff\MovieTariff;
use App\Models\MovieTimes\MovieTime;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\Products\Product;
use App\Models\PromotionCorporative\PromotionCorporative;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\TypePaymentMethods\TypePaymentMethod;
use App\SearchableRules\TicketPromotionSearchableRule;
use App\Services\Searchable\Searchable;
use App\User;
use Carbon\Carbon;

class TicketPromotionRepository implements TicketPromotionRepositoryInterface
{
    private $searchableService;
    private $repository;

    public function __construct(Searchable $searchableService, TicketPromotion $repository)
    {
        $this->searchableService = $searchableService;
        $this->repository = $repository;
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $promotion = TicketPromotion::where('code', $data['code'])->first();
        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            if($promotion)
                $promotion->delete();

            return;
        }
        //$headquarter = Headquarter::where('point_sale', $data['point_sale'])->first();
        $tariff = $data['tariff'] ? MovieTariff::where('remote_funtar', $data['tariff']['tarcod'])->first() : null;
        if (!$tariff && !is_null($data['tariff'])) {
            $tariff = MovieTariff::create([
                'name' => $data['tariff']['tarnom'],
                'remote_funtar' => $data['tariff']['tarcod']
            ]);
        }
        $type_method = $data['payment_method'] ? TypePaymentMethod::where('remote_code', $data['payment_method']['remote_code'])->first() : null;
        if (!$type_method && !is_null($data['payment_method'])) {
            $type_method = TypePaymentMethod::create([
                'remote_code' => $data['payment_method']['remote_code'],
                'name' => $data['payment_method']['name'],
                'type_currency' => $data['payment_method']['type_currency'],
                'payment_type' => $data['payment_method']['payment_type']
            ]);
        }

        $product = Product::where('code', $data['product_code'])->first();

        if (isset($promotion->id)) {
            TicketPromotion::where('id', $promotion->id)
                ->update([
                    'name' => $data['name'],
                    'tickets_number' => $data['tickets_number'],
                    'price_second_ticket' => $data['price_second_ticket'],
                    'discount_rate' => $data['discount_rate'],
                    'product_id' => $product ? $product->id : null,
                    'price_ticket' => $data['price_ticket'],
                    'price_product' => $data['price_product'],
                    'membership_card_required' => $data['membership_card_required'],
                    'is_block_3d' => $data['is_block_3d'],
                    'is_block_1s' => $data['is_block_1s'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'is_block_sunday' => $data['is_block_sunday'],
                    'is_block_monday' => $data['is_block_monday'],
                    'is_block_tuesday' => $data['is_block_tuesday'],
                    'is_block_wednesday' => $data['is_block_wednesday'],
                    'is_block_thursday' => $data['is_block_thursday'],
                    'is_block_friday' => $data['is_block_friday'],
                    'is_block_saturday' => $data['is_block_saturday'],
                    'movie_chain' => $data['movie_chain'],
                    'tariff_type' => $data['tariff_type'],
                    'type_payment_method_id' => $type_method ? $type_method->id : null,
                    'headquarter_id' => $syncHeadquarter ? null : null,
                    'max_num_tickets' => trim($data['max_num_tickets']) == "" ? 0 : $data['max_num_tickets'],
                    'promotion_type' => $data['promotion_type'],
                    'promo_tickets_number' => $data['promo_tickets_number'],
                ]);
        } else {
            TicketPromotion::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'tickets_number' => $data['tickets_number'],
                'price_second_ticket' => $data['price_second_ticket'],
                'discount_rate' => $data['discount_rate'],
                'product_id' => $product ? $product->id : null,
                'price_ticket' => $data['price_ticket'],
                'price_product' => $data['price_product'],
                'membership_card_required' => $data['membership_card_required'],
                'is_block_3d' => $data['is_block_3d'],
                'is_block_1s' => $data['is_block_1s'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_block_sunday' => $data['is_block_sunday'],
                'is_block_monday' => $data['is_block_monday'],
                'is_block_tuesday' => $data['is_block_tuesday'],
                'is_block_wednesday' => $data['is_block_wednesday'],
                'is_block_thursday' => $data['is_block_thursday'],
                'is_block_friday' => $data['is_block_friday'],
                'is_block_saturday' => $data['is_block_saturday'],
                'movie_chain' => $data['movie_chain'],
                'tariff_type' => $data['tariff_type'],
                'type_payment_method_id' => $type_method ? $type_method->id : null,
                'headquarter_id' => $syncHeadquarter ? null : null,
                'max_num_tickets' => trim($data['max_num_tickets']) == "" ? 0 : $data['max_num_tickets'],
                'promotion_type' => $data['promotion_type'],
                'promo_tickets_number' => $data['promo_tickets_number'],
            ]);
        }
    }

    public function listByMovieTime($movie_time)
    {
        /**@var User $user * */
        $user = FunctionHelper::getApiUser();
        $query = $this->queryPromotion($movie_time)
            ->doesnthave('award')
            ->where('promotion_type', '<>', TicketPromotionType::CORPORATIVE)
            ->whereNotIn('code', TicketPromotionForAllTariffs::codes());

        if($user == null)
        {
            // exclude birthday promotion
            $query->where('promotion_type', '<>', TicketPromotionType::BIRTHDAY);
        } else {
            if (!$user->customer->today_birthday($movie_time)) {
                $query->whereNotNull('tariff_type')
                    ->where('tariff_type', '<>', '');
            }
        }

        return $query->get();
    }

    public function searchBO($request)
    {
        $query = TicketPromotion::with(['product', 'movie_tariff', 'payment_method_type', 'award']);
        $this->searchableService->applyArray($query, new TicketPromotionSearchableRule(), $request);
        return $query->orderBy('end_date', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(Helper::perPage($request));
    }

    public function searchPromotion($request)
    {
        $movie_time = MovieTime::find($request->movie_time_id);
    }

    private function validPromotion($movie_time, $code = null)
    {
        return TicketPromotion::where(function ($query) {
            $query->where('movie_chain', Helper::getTradeNameHeader())
                ->orwhereNull('movie_chain');
        })->where(function ($query) use ($movie_time) {
            $query->where('headquarter_id', $movie_time->headquarter_id)
                ->orwhereNull('headquarter_id');
        })->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where(function ($query) {
                $today = Carbon::now()->formatLocalized('%A');
                if ($today == 'Sunday') {
                    $query->where('is_block_sunday', 0);
                } else if ($today == 'Monday') {
                    $query->where('is_block_monday', 0);
                } else if ($today == 'Tuesday') {
                    $query->where('is_block_tuesday', 0);
                } else if ($today == 'Wednesday') {
                    $query->where('is_block_wednesday', 0);
                } else if ($today == 'Thuerday') {
                    $query->where('is_block_thursday', 0);
                } else if ($today == 'Friday') {
                    $query->where('is_block_friday', 0);
                } else if ($today == 'Saturday') {
                    $query->where('is_block_saturday', 0);
                }
            })->when(!is_null($code), function ($query) use ($code) {
                $query->where('code', $code);
            })->get();
    }

    public function promotionByCode($request, $code)
    {
        $promotion_corporative = PromotionCorporative::where('codigo', $code)
            ->where('estado', 0)
            ->first();
        if (!$promotion_corporative) {
            throw new \Exception("Código no existe");
        }

        $movie_time = MovieTime::find($request->movie_time_id);
        $promotions = $this->validPromotion($movie_time, $promotion_corporative->promotion_code);
        if (!$promotions->count()) {
            throw new \Exception("Promoción no disponible");
        }
        $tickets = collect($request->tickets);
        $promotion = $promotions->first();
        $quantity = $request->has('quantity') ? $request->quantity : 1;
        $tickets = $this->addTickets($promotion, $tickets, $movie_time, $quantity, $code);

        return ['tickets' => $tickets];
    }

    public function promotionForTariff($movie_time)
    {
        $query = $this->queryPromotion($movie_time);
        return $query->whereIn('code', TicketPromotionForAllTariffs::codes())
            ->whereDoesntHave('award')
            ->first();
    }

    public function addTickets($promotion, $tickets, $movie_time, $quantity, $code = null, $points = null)
    {
        $exists = false;
        $promotions_tickets = $promotion->award ? $tickets->whereNotNull('ticket_award_id') : $tickets->whereNotNull('ticket_promotion_id');
        $tariff = MovieTimeTariff::whereHas('movie_tariff', function ($query) use ($promotion) {
            $query->where('remote_funtar', $promotion->tariff_type);
        })->where('movie_time_id', $movie_time->id)->first();

        [$amount, $prices] = $this->aplicatePromotion($tariff, $promotion);

        if ($promotions_tickets->count()) {
            foreach ($promotions_tickets as $i => $item) {
                if (array_key_exists('ticket_award_id', $item)) {
                    $model = TicketPromotion::whereHas('award', function ($query) use ($item) {
                        $query->where('id', $item['ticket_award_id']);
                    })->first();
                } else {
                    if (array_key_exists('code', $item) && $code && in_array($code, $item['code'])) {
                        throw new \Exception("Código ya usado");
                    }
                    $model = TicketPromotion::find($item['ticket_promotion_id']);
                }

                $movie_time_tariff = MovieTimeTariff::whereHas('movie_tariff', function ($query) use ($model) {
                    $query->where('remote_funtar', $model->tariff_type);
                })->where('movie_time_id', $movie_time->id)->first();
                [$amount_promotion, $prices_promotion] = $this->aplicatePromotion($movie_time_tariff, $model);
                if ($model->award) {
                    if ($item['ticket_award_id'] == $promotion->award->id) {
                        $exists = true;
                        $new_quantity = $quantity + $item['quantity'];
                        $new_points = $points + ($model->award->points * $item['quantity']);
                        $new_ticket_quantity = $model->tickets_number * $new_quantity;
                    } else {
                        $new_quantity = $item['quantity'];
                        $new_points = $model->award->points * $new_quantity;
                        $new_ticket_quantity = $model->tickets_number * $new_quantity;
                    }

                    $info = [
                        'ticket_award_id' => $model->award->id,
//                        'ticket_promotion_name' => $model->name,
                        'points' => $new_points,
                    ];
                } else {
                    if ($item['ticket_promotion_id'] == $model->id) {
                        $exists = true;
                        $new_quantity = $quantity + $item['quantity'];
                        $new_ticket_quantity = $model->tickets_number * $new_quantity;
                    } else {
                        $new_quantity = $item['quantity'];
                        $new_ticket_quantity = $model->tickets_number * $new_quantity;
                    }
                    $info = [
                        'ticket_promotion_id' => $model->id,
//                        'ticket_promotion_name' => $model->name,
                    ];
                    if ($code) {
                        $codes = $item['code'];
                        $codes[] = $code;
                        $info = array_merge($info, [
                            'code' => $codes
                        ]);
                    }
                }
                $tickets[$i] = array_merge($info, [
                    'movie_time_tariff_id' => $movie_time_tariff->id,
                    'quantity' => $new_quantity,
                    'ticket_quantity' => $new_ticket_quantity,
                    'amount' => $amount_promotion * $new_quantity,
                ]);
            }

        }


        if (!$exists) {
            $ticket_quantity = $promotion->tickets_number * $quantity;
            if ($promotion->award) {
                $ticket_new = [
                    'ticket_award_id' => $promotion->award->id,
//                    'ticket_promotion_name' => $promotion->name,
                    'points' => $promotion->award->points * $quantity,
                ];
            } else {
                $ticket_new = [
                    'ticket_promotion_id' => $promotion->id,
//                    'ticket_promotion_name' => $promotion->name,
                ];
            }
            $ticket_new = array_merge($ticket_new, [
                'movie_time_tariff_id' => $tariff->id,
                'quantity' => $quantity,
                'ticket_quantity' => $ticket_quantity,
                'amount' => $amount * $quantity,
            ]);
            if ($code) {
                $ticket_new = array_merge($ticket_new, [
                    'code' => [$code]
                ]);
            }

            $tickets[] = $ticket_new;

        }
        return $tickets;
    }

    public function aplicatePromotion($motive_time_tariff, $promotion)
    {
        $price = $this->tariffPrice($motive_time_tariff);
        if ($promotion->tickets_number == 2 && $promotion->price_second_ticket > 0) {
            $amount = $price + $promotion->price_second_ticket;
            $prices = [$price, $promotion->price_second_ticket];
        } else if ($promotion->tickets_number == 2 && $promotion->discount_rate >= 0) {
            $discount_rate = $promotion->discount_rate;
            if ($promotion->discount_rate < 1 && $promotion->promotion_type == 4) {
                $discount_rate = 50;
            }
            $amount = ($price * 2) - ($price * 2 * ($discount_rate / 100));
            $amount = floatval(number_format($amount, 2));
            $prices = [$price, $amount];
            //$amount += $price;
        } else if ($promotion->tickets_number == 1 && $promotion->discount_rate > 0) {
            $amount = $price - ($price * ($promotion->discount_rate / 100));
            $amount = floatval(number_format($amount, 2));
            $prices = [$amount];
        }else if($promotion->isBirthday){
            $amount= 0;
            $prices = [$amount, $amount];
        } else {
            throw new \Exception('Promoción no configurada');
        }
        return [$amount, $prices];
    }

    public function tariffPrice($movie_time_tariff)
    {
        $price = $movie_time_tariff->online_price;
        if ($movie_time_tariff->movie_tariff->remote_funtar == TariffType::PLANA) {
            $settingRepository = \App::make(SettingRepositoryInterface::class);
            $setting = $settingRepository->getCommunitySystemVars($movie_time_tariff->movie_time->headquarter_id);
            $price = $setting['config']['tarzzz'];
        }
        return $price;
    }

    public function consultPromotionByCode($request, $code)
    {
        $promotion_corporative = PromotionCorporative::where('codigo', $code)
            ->where('estado', 0)
            ->first();
        if (!$promotion_corporative) {
            throw new \Exception("Código no existe");
        }

        $movie_time = MovieTime::find($request->movie_time_id);
        $promotions = $this->validPromotion($movie_time, $promotion_corporative->promotion_code);
        if (!$promotions->count()) {
            throw new \Exception("Promoción no disponible");
        }

        return $promotions->first();
    }

    private function queryPromotion($movie_time)
    {
        return TicketPromotion::where(function ($query) {
            $query->where('movie_chain', Helper::getTradeNameHeader())
                ->orwhereNull('movie_chain');
        })->where(function ($query) use ($movie_time) {
            $query->where('headquarter_id', $movie_time->headquarter_id)
                ->orwhereNull('headquarter_id');
        })->when($movie_time->movie->is_3d == 1, function ($query) use ($movie_time) {
            return $query->where('is_block_3d', 0);

        })->when(Carbon::parse($movie_time->date_start) < $movie_time->movie->last_premier_date, function ($query) use ($movie_time) {
            return $query->where('is_block_1s', 0);
        })
            ->where('start_date', '<=', Carbon::parse($movie_time->date_start))
            ->where('end_date', '>=', Carbon::parse($movie_time->date_start))
            ->where(function ($query) use ($movie_time) {
                $today = Carbon::parse($movie_time->date_start)->formatLocalized('%A');
                if ($today == 'Sunday') {
                    $query->where('is_block_sunday', 0);
                } else if ($today == 'Monday') {
                    $query->where('is_block_monday', 0);
                } else if ($today == 'Tuesday') {
                    $query->where('is_block_tuesday', 0);
                } else if ($today == 'Wednesday') {
                    $query->where('is_block_wednesday', 0);
                } else if ($today == 'Thursday') {
                    $query->where('is_block_thursday', 0);
                } else if ($today == 'Friday') {
                    $query->where('is_block_friday', 0);
                } else if ($today == 'Saturday') {
                    $query->where('is_block_saturday', 0);
                }
            });
    }

    public function allForApi()
    {
        $tradeName = Helper::getTradeNameHeader();
        $notMovieChain = $tradeName == TradeName::CINESTAR ? MovieChainEnum::MOVIETIME : MovieChainEnum::CINESTAR;

        return TicketPromotion::whereDate('end_date', '>=', now()->toDateString())
            ->whereRaw('IFNULL(movie_chain, 0) <> '. $notMovieChain)
            ->whereDoesntHave('award')
            ->orderBy('name')
            ->get();
    }

    public function update($data, $request)
    {
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::TICKET_PROMOTION_FOLDER, $request->file('image'));
            $data->image = $file_name;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }

        $data->save();
        return $data;
    }
}
