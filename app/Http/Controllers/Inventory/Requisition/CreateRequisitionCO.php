<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CreateRequisitionCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54005,'user_id'=>$this->user_id
            ]);

        $locations = Location::query()->where('company_id',$this->company_id)
                    ->where('location_type','F')
            ->orderBy('name')->pluck('name','id');

        return view('inventory.requisition.create-requisition-index',compact('locations'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','tax_id','unit_price','unit_name')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try{

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RQ')
                ->where('fiscal_year',$this->get_fiscal_year(Carbon::now()->format('Y-m-d')))
                ->lockForUpdate()->first();

            $req_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['ref_no'] = $req_no;
            $request['req_date'] = Carbon::now();
            $request['user_id'] = $this->user_id;

            $inserted = Requisition::query()->create($request->all()); //Insert Data Into Requisition Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {

                    $requisition_item['company_id'] = $this->company_id;
                    $requisition_item['ref_no'] = $req_no;
                    $requisition_item['ref_id'] = $inserted->id;
                    $requisition_item['ref_type'] = 'R'; //Requisition
                    $requisition_item['to_whom'] = $item['requisition_for'];
                    $requisition_item['tr_date']= Carbon::now();
                    $requisition_item['product_id'] = $item['item_id'];
                    $requisition_item['quantity'] = $item['quantity'];
                    $requisition_item['remarks'] = $item['remarks'];

                    TransProduct::query()->create($requisition_item);

                    $request->session()->flash('alert-success', 'Requisition Data Successfully Completed For Approval');

                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RQ')
                ->increment('last_trans_id');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Requisition Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Requisition\CreateRequisitionCO@index')->with('success','Requisition Data Saved');

    }
}
