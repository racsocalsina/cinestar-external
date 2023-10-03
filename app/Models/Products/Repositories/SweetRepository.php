<?php


namespace App\Models\Products\Repositories;


use App\Models\CustomerProductFavorites\CustomerProductFavorite;
use App\Models\Customers\Customer;
use App\Models\Products\Product;
use App\Models\Products\Repositories\Interfaces\SweetRepositoryInterface;
use App\SearchableRules\ProductSearchableRule;
use App\Services\Searchable\Searchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class SweetRepository implements SweetRepositoryInterface
{
    private $searchableService;

    public function __construct(Searchable $searchableService)
    {
        $this->searchableService = $searchableService;
    }

    public function searchFavoriteApi(array $params)
    {
        $params['customer_id'] = Customer::where('user_id', $params['user_id'])->first()->id;

        $query = Product::select([
            'products.id', 'products.name', 'products.image', 'headquarter_product.price', 'customer_product_favorite.id as favorite_id',
            'products.product_type_id as type_id', DB::raw("(case when is_combo = 1 then 'combo' else 'product' end) as sweet_type")
        ])
            ->join('headquarter_product', 'products.id', 'headquarter_product.product_id')
            ->join('customer_product_favorite', function($join) use ($params){
                $join->on('products.id', '=', 'customer_product_favorite.product_id');
                $join->on('customer_product_favorite.customer_id', '=', DB::raw($params['customer_id']));
            })
            ->where('headquarter_product.headquarter_id', $params['headquarter_id'])
            ->where('headquarter_product.active', 1)
            ->where('headquarter_product.stock', '>', 0);

            $hoy = date('Y-m-d');

            if ($params['presale_date'] == $hoy) {
                #1. Incluir todos los productos que no tengan periodo de preventa (Inicio o fin sean nulos)
                #2. Incluir los productos que estén dentro del periodo de preventa (Check preventa y fecha dentro del rango)
                $query->where(function (Builder $queryPresale) use ($hoy) {
                    $queryPresale->where(function (Builder $queryPresaleA) {
                        $queryPresaleA->where('headquarter_product.is_presale', false);
                    });
                    $queryPresale->orWhere(function (Builder $queryPresaleB) use ($hoy) {
                        $queryPresaleB->where('headquarter_product.is_presale', true);
                        $queryPresaleB->whereDate('headquarter_product.presale_start', '<=', $hoy);
                        $queryPresaleB->whereDate('headquarter_product.presale_end', '>=', $hoy);
                    });
                });
    
            } else {
                #1. Incluir los productos que estén dentro del periodo de preventa (Check preventa y fecha dentro del rango)
                    $query->where('headquarter_product.is_presale', true);
                    $query->whereDate('headquarter_product.presale_start', '<=', $params['presale_date']);
                    $query->whereDate('headquarter_product.presale_end', '>=',$params['presale_date']);
             
            }

        $this->searchableService->applyArray($query, new ProductSearchableRule(), $params);

        return $query
            ->orderBy('favorite_id', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function toggleFavorite($params)
    {
        $customer = Customer::where('user_id', $params['user_id'])->first();
        $favorite = null;
        $entity = null;

        $entity = CustomerProductFavorite::query();
        $favorite = $entity->where('customer_id', $customer->id)
            ->where('product_id', $params['id'])
            ->first();

        $paramsToEntity = [
            'customer_id' => $customer->id,
            'product_id' => $params['id']
        ];

        if ($favorite) {
            $favorite->delete();
        } else {
            $entity->create($paramsToEntity);
        }
    }
}

