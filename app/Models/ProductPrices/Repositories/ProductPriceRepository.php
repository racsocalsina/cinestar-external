<?php

namespace App\Models\ProductPrices\Repositories;

use App\Enums\GlobalEnum;
use App\Models\HeadquarterProducts\HeadquarterProduct;
use App\Models\Headquarters\Headquarter;
use App\Models\ProductPrices\Repositories\Interfaces\ProductPriceRepositoryInterface;
use App\Models\Products\Product;

class ProductPriceRepository implements ProductPriceRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];
        $product = Product::where('code', $data['product_code'])->first();

        if (isset($product->id)) {

            $headquarterProduct = HeadquarterProduct::where('product_id', $product->id)
                ->where('headquarter_id', $syncHeadquarter->id)
                ->first();

            if ($action == GlobalEnum::ACTION_SYNC_DELETE && !is_null($headquarterProduct)) {
                // set price as null
                $headquarterProduct->update([
                    'price' => null
                ]);
                return;
            }

            if ($action == GlobalEnum::ACTION_SYNC_UPDATE && !is_null($headquarterProduct))
            {
                $headquarterProduct->update([
                    'price'             => floatval($data['price']) == 0 ? null : $data['price'],
                    'remote_price_code' => $data['remote_code']
                ]);
            }

            if ($action == GlobalEnum::ACTION_SYNC_INSERT) {
                $arrayBody = [
                    'headquarter_id'    => $syncHeadquarter->id,
                    'product_id'        => $product->id,
                    'remote_price_code' => $data['remote_code'],
                    'price'             => floatval($data['price']) == 0 ? null : $data['price'],
                    'active'            => $data['product']['active'],
                    'igv'               => $data['product']['igv'],
                    'isc'               => $data['product']['isc'],
                    'stock'             => $data['product']['stock'],
                    'sales_unit'        => $data['product']['sales_unit'],
                ];

                if(is_null($headquarterProduct))
                {
                    HeadquarterProduct::create($arrayBody);
                } else {
                    $headquarterProduct->update($arrayBody);
                }
            }
        }
    }
}
