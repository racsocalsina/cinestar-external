<?php


namespace App\Scopes;


use App\Helpers\Helper;
use App\Models\Banners\Banner;
use App\Models\Cities\City;
use App\Models\Customers\Customer;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Purchases\Purchase;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TradeNameScope implements Scope
{
    private $tradeName;

    public function apply(Builder $builder, Model $model)
    {
        $this->tradeName = Helper::getTradeNameHeader();

        // skip scope because the header Trade-Name is neccesary
        if($this->tradeName == null)
            return $builder;

        if(!Helper::tradeNameExist($this->tradeName))
            return $builder;

        // Apply scopes
        if($model instanceof User)
            return $this->tradeNameScope($builder);

        if($model instanceof Customer)
            return $this->tradeNameScope($builder);

        if($model instanceof Headquarter)
            return $this->tradeNameScope($builder);

        if($model instanceof MovieTime)
            return $this->movieTimeScope($builder);

        if($model instanceof Purchase)
            return $this->purchaseScope($builder);

        if($model instanceof Banner)
            return $this->tradeNameScope($builder);

        if($model instanceof City)
            return $this->tradeNameScope($builder);

        // In the case of the back-office, simply do not send the header called "Trade-Name",
        // so will show all data
        return $builder;
    }

    private function movieTimeScope($builder)
    {
        return $builder->whereHas('headquarter', function ($query) {
            $query->where('trade_name', $this->tradeName);
        });
    }

    private function purchaseScope($builder)
    {
        return $builder->whereHas('headquarter', function ($query) {
            $query->where('trade_name', $this->tradeName);
        });
    }

    private function tradeNameScope($builder)
    {
        return $builder->where('trade_name', $this->tradeName);
    }
}
