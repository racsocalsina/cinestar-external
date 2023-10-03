<?php


namespace App\Models\Tickets;


use App\Enums\TariffType;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\PurchaseItems\PurchaseItem;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\Purchases\Purchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        'purchase_id',
        'purchase_item_id',
        'movie_time_tariff_id',
        'chair_row',
        'chair_column',
        'planner_index',
        'seat_name',
        'uuid',
        'purchase_promotion_id',
        'status'
    ];

    public function movie_time_tariff() {
        return $this->belongsTo(MovieTimeTariff::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function purchaseItem() {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function promotion() {
        return $this->belongsTo(PurchasePromotion::class, 'purchase_promotion_id');
    }

    // Scopes
    public function scopeMovieTariff4X(Builder $builder, $is_4X, $row_num)
    {
        $cond = $is_4X ? 'LIKE' : 'NOT LIKE';
        if($is_4X && $row_num == 1){
            return $builder->whereHas('movie_time_tariff', function ($query) use ($cond) {
                $query->whereHas('movie_tariff', function ($q) use ($cond) {
                    $q->where('name', $cond, '%'.TariffType::VIP.'%');
                });
            });
        }elseif($is_4X){
            return $builder->whereHas('movie_time_tariff', function ($query) use ($cond) {
                $query->whereHas('movie_tariff', function ($q) use ($cond) {
                    $q->where('name', $cond, '%'.TariffType::FOUR_X.'%');
                });
            });
        }else{
            return $builder->whereHas('movie_time_tariff', function ($query) use ($cond) {
                $query->whereHas('movie_tariff', function ($q) use ($cond) {
                    $q->where('name', $cond, '%'.TariffType::FOUR_X.'%')
                    ->where('name', $cond, '%'.TariffType::VIP.'%');
                });
            });
        }
    }
}
