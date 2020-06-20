<?php


namespace App\Traits;


trait ProductHistoryTrait
{
    public function historyEntry($company_id, $input)
    {


//        $history['company_id'] = $company_id;
//        $history['ref_no'] = $receive_no;
//        $history['ref_id'] = $inserted->id;
//        $history['ref_type'] = 'R'; //Sales
//        $history['relationship_id'] = $data->relationship_id;
//        $history['tr_date']= $request['receive_date'];
//        $history['product_id'] = $data->product_id;
//        $history['name'] = $products->where('id',$data->product_id)->first()->name;
//        $history['quantity_in'] = $item['receive'];
//        $history['received'] = $item['receive'];
//        $history['unit_price'] = $data->unit_price;
//        $history['total_price'] = $data->total_price;

        return $input;
    }

}
