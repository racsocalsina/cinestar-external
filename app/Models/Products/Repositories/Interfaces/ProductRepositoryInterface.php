<?php


namespace App\Models\Products\Repositories\Interfaces;


use App\Models\Products\Product;

interface ProductRepositoryInterface
{
    public function queryable();
    public function sync($body, $headquarter = null);
    public function searchApi(array $params);
    public function searchApiPromotions($headquarter_id, $movie_time_id, $product_type_id,$today);
    public function applicatePromotionPrice($headquarter_id, $product, $choco_promotion_product);
    public function searchBo(array $params);
    public function search($params);
    public function getTotals($params);
    public function getAvailableHeadquarters(Product $product, $byCombo = false);
    public function update($product, $request);
}
