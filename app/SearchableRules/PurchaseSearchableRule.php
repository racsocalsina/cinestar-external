<?php


namespace App\SearchableRules;


use App\Services\Searchable\ByColumnSearchable;
use Illuminate\Support\Facades\DB;

class PurchaseSearchableRule implements ByColumnSearchable
{
    public function searchableColumns(): array
    {
        return [
            'id'               => ['condition' => '='],
            'headquarter_id'   => ['condition' => '='],
            'movie_id'         => ['condition' => '='],
            'user_id'          => ['condition' => '='],
            'status'           => ['condition' => '='],
            'transaction_status' => ['condition' => '='],
            'origin'           => ['condition' => '='],
            'remote_movkey'    => function ($query, $value) {
                $query->whereExists(function ($q) use ($value, $query) {
                    $ps = DB::table('purchase_sweets')
                        ->select(DB::raw("1"))
                        ->where('purchase_sweets.purchase_id', DB::raw("purchases.id"))
                        ->where('purchase_sweets.remote_movkey', 'like', '%' . $value . '%');

                    $q->select(DB::raw(1))
                        ->from('purchase_tickets')
                        ->where('purchase_tickets.purchase_id', DB::raw("purchases.id"))
                        ->where('purchase_tickets.remote_movkey', 'like', '%' . $value . '%')
                        ->union($ps);
                });
            },
            'typeClient' => function ($query, $value) {
                if($value=='invitado'){
                    $query->whereRaw('user_id is null');
                }else{
                    $query->whereRaw('user_id is not null');
                }
            },
            'start_created_at' => function ($query, $value) {
                $query->whereDate('created_at', '>=', $value);
            },
            'end_created_at'   => function ($query, $value) {
                $query->whereDate('created_at', '<=', $value);
            },
            'movie_time_date' => function ($query, $value) {
                $query->whereHas('movie_time', function ($sub_query) use ($value) {
                    $sub_query->whereDate('movie_times.date_start', $value);
                });
            },
            'movie_time_time' => function ($query, $value) {
                $query->whereHas('movie_time', function ($sub_query) use ($value) {
                    $sub_query->where('movie_times.time_start', $value);
                });
            },
            'document_number' => function ($query, $value) {
                $query->whereHas('user', function ($sub_query) use ($value) {
                    $sub_query->whereHas('customer', function ($sub_sub_query) use ($value) {
                        $sub_sub_query->where('customers.document_number', $value);
                    });
                });
            }
        ];
    }
}

