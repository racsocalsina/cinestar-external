<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;

class ProductSearchableRule implements ByColumnSearchable
{

    public function searchableColumns(): array
    {
        return [
            'name',
            'is_combo'        => ['condition' => '='],
            'product_type_id' => ['condition' => '='],
/*             'presale_date'   => function ($query, $value) {
                $query->whereDate('presale_start','<=', $value)
                ->whereDate('presale_end','>=', $value)
                ->get();
            }, */
            'combo_type_id'   => function ($query, $value) {
                $query->where('product_type_id', '=', $value);
            },
            'type_id'         => function ($query, $value) {
                $query->where('product_type_id', '=', $value);
            },
            'headquarter_id'  => function ($query, $value) {
                $query->whereHas('headquarters', function ($sub_query) use ($value) {
                    return $sub_query->where('headquarter_id', $value);
                });
            },
            'is_available'    => ['condition' => '='],
        ];
    }
}
