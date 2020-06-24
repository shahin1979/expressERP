<?php


namespace App\Traits;


use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Product\ProductMO;

trait ProductTrait
{

    public function get_lifo_price($id,$company_id)
    {
        $product = ProductMO::query()->find($id);
        $history = ProductHistory::query()->where('product_id',$id)
            ->where('company_id',$company_id)
            ->where('ref_type','P')
            ->orderBy('tr_date','DESC')
            ->get();

        $on_hand = $product->on_hand;
        $p_qty = 0;
        $ref_no = null;
        $unit_price = $product->unit_price;
        $find = false;

        foreach ($history as $row)
        {
            $p_qty = $p_qty + $row->quantity_in;

            if ($p_qty >= $on_hand) {
                $unit_price = $row->unit_price;
                $find = true;
                break;
            }
        }

        if($find = false)
        {
            $unit_price = round($product->opening_value/$product->opening_qty);
        }

        return $unit_price;
    }

    public function get_average_price($id,$company_id)
    {
        $product = ProductMO::query()->find($id);
        $history = ProductHistory::query()->where('product_id',$id)
            ->where('company_id',$company_id)
            ->where('ref_type','P')
            ->orderBy('tr_date','DESC')
            ->get();

        $on_hand = $product->on_hand;
        $p_qty = 0;
        $unit_price = 0;
        $find = false;
        $p_value = 0;

        foreach ($history as $row)
        {
            $p_qty = $p_qty + $row->quantity_in;
            $p_value = $p_value + $row->quantity_in * $row->unit_price;

            if ($p_qty >= $on_hand) {

                $unit_price = round($p_value/$p_qty);
                $find = true;
                break;
            }
        }

        if($find == false)
        {
            $opn_qty = $on_hand - $p_qty;
            $opn_amt = ($product->opening_value/$product->opening_qty) * $opn_qty;
            $unit_price = round(($opn_amt + $p_value)/$on_hand);
        }

        return $unit_price;
    }

}
