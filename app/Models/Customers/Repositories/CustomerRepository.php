<?php


namespace App\Models\Customers\Repositories;


use App\Enums\SoldItemTypes;
use App\Helpers\Helper;
use App\Models\Customers\Customer;
use App\Models\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Services\Searchable\Searchable;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $model;
    private $searchableService;

    public function __construct(Customer $model, Searchable $searchableService)
    {
        $this->model = $model;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function search(array $params)
    {
        $query = $this->queryable();
        //$this->searchableService->applyArray($query, new CustomerSearchableRule(), $params);
        return $query->with('department')->paginate(Helper::perPage($params));
    }

    public function ranking(array $params)
    {
        $month = array_key_exists('month', $params) ? $params['month'] : null;
        $start_date = array_key_exists('start_date', $params) ? $params['start_date'] : null;
        $end_date = array_key_exists('end_date', $params) ? $params['end_date'] : null;
        $headquarter_id = array_key_exists('headquarter_id', $params) ? $params['headquarter_id'] : null;
        $type = array_key_exists('type', $params) ? $params['type'] : null;

        $query = $this->queryable()
            ->select('customers.*', DB::raw("SUM(purchase_items.paid_amount) as amount"))
            ->join('users', 'customers.user_id', 'users.id')
            ->leftJoin('purchases', 'users.id', 'purchases.user_id')
            ->join('purchase_items', 'purchases.id', 'purchase_items.purchase_id')
            ->when($month, function ($q) use ($month) {
                $q->whereRaw('MONTH(purchases.created_at) =' . $month);
            })->when($start_date, function ($q) use ($start_date, $end_date) {
                $q->whereDate('purchases.created_at', '>=', $start_date)
                    ->whereDate('purchases.created_at', '<=', $end_date);
            })->when($headquarter_id, function ($q) use ($headquarter_id) {
                $q->where('purchases.headquarter_id', $headquarter_id);
            })->when($type, function ($q) use ($type) {
                if ($type == SoldItemTypes::TICKET){
                    $q->whereNotNull('purchase_items.purchase_ticket_id');
                }else{
                    $q->whereNotNull('purchase_items.purchase_sweet_id');
                }

            })->groupBy('customers.id')
            ->orderby('amount', 'desc');

        return $query->paginate(Helper::perPage($params));
    }
}
