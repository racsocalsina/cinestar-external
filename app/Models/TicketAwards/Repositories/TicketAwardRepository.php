<?php


namespace App\Models\TicketAwards\Repositories;


use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Products\Product;
use App\Models\TicketAwards\Repositories\Interfaces\TicketAwardRepositoryInterface;
use App\Models\TicketAwards\TicketAward;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\User;
use App\SearchableRules\TicketAwardSearchableRule;
use App\Services\Searchable\Searchable;
use Carbon\Carbon;

class TicketAwardRepository implements TicketAwardRepositoryInterface
{
    private $searchableService;
    private $promotionRepository;

    public function __construct(Searchable $searchableService, TicketPromotionRepositoryInterface $promotionRepository)
    {
        $this->searchableService = $searchableService;
        $this->promotionRepository = $promotionRepository;
    }

    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $award = TicketAward::where('code', $data['code'])->first();
        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            $award->delete();
            return;
        }

        $product = Product::where('code', $data['product_code'])->first();
        $promotion = TicketPromotion::where('code', $data['promotion_code'])->first();

        if (isset($award->id)) {
            TicketAward::where('code', $data['code'])->update([
                'name' => $data['name'],
                'points' => $data['points'],
                'product_id' => isset($product) ? $product->id : null,
                'restrictions' => $data['restrictions'],
                'unit' => $data['unit'],
                'ticket_promotion_id' => isset($promotion) ? $promotion->id : null,
            ]);
        } else {
            TicketAward::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'points' => $data['points'],
                'product_id' => isset($product) ? $product->id : null,
                'restrictions' => $data['restrictions'],
                'unit' => $data['unit'],
                'ticket_promotion_id' => isset($promotion) ? $promotion->id : null,
            ]);
        }
    }

    public function searchBO($request)
    {
        $query = TicketAward::with(['product', 'promotion']);
        $this->searchableService->applyArray($query, new TicketAwardSearchableRule(), $request);
        return $query->paginate(Helper::perPage($request));
    }

    private function queryTicketAward($movie_time)
    {
        $user = FunctionHelper::getApiUser();

        if(!$user)
            return null;

        return TicketAward::with(['product', 'promotion'])
            ->whereHas('promotion', function ($query) use ($movie_time) {
                $query->where(function ($query) {
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
            })->where('points', '<=', $user->customer->user_partner_cod->ticket_points);
    }

    private function validPromotion($promotion, $movie_time)
    {
        return TicketPromotion::where('id', $promotion->id)->with(['product', 'promotion'])
            ->where(function ($query) {
                $query->where('movie_chain', Helper::getTradeNameHeader())
                    ->orwhereNull('movie_chain');
            })->where(function ($query) use ($movie_time) {
                $query->where('headquarter_id', $movie_time->headquarter_id)
                    ->orwhereNull('headquarter_id');
            })->when($movie_time->movie->is_3d == 1, function ($query) use ($movie_time) {
                return $query->where('is_block_3d', 0);

            })->when(Carbon::parse($movie_time->date_start) < $movie_time->movie->last_premier_date, function ($query) use ($movie_time) {
                return $query->where('is_block_1s', 0);
            })->where('start_date', '<=', Carbon::parse($movie_time->date_start))
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
            })->count();

    }

    public function getData()
    {
        return TicketAward::with(['product', 'promotion'])
            ->whereHas('promotion')
            ->whereNotNull('product_id')
            ->whereNotNull('ticket_promotion_id')
            ->get();
    }

    public function valid($request)
    {
        $user = FunctionHelper::getApiUser();
        $movie_time = MovieTime::find($request->movie_time_id);

        $awards = collect($request->awards);
        $points_use = 0;
        foreach ($awards as $award) {
            $model = TicketAward::find($award['ticket_award_id']);
            if (!$this->validPromotion($model->promotion, $movie_time)) {
                throw new \Exception("Premio {$model->name} no esta disponible");
            }
            $points_use+=  $model->points * $award['quantity'];
        }

        if(!$user)
            throw new \Exception(__('app.purchases.awards_not_allowed_for_guest'));

        if ($user->customer->user_partner_cod->ticket_points < $points_use) {
            throw new \Exception(__('app.points.insufficient_points'));
        }

        $tickets = $this->promotionRepository->addTickets($model->promotion, $tickets, $movie_time, $quantity, null, $points);
        return ['tickets' => $tickets];
    }

    public function allForApi()
    {
        return TicketAward::orderBy('name')->get();
    }

    public function update($data, $request)
    {
        if ($request->has('image')) {
            $file_name = FileHelper::saveFile(env('BUCKET_ENV') . GlobalEnum::TICKET_AWARDS_FOLDER, $request->file('image'));
            $data->image = $file_name;
        }

        if ($request->has('description')) {
            $data->description = $request->description;
        }

        $data->save();
        return $data;
    }
}
