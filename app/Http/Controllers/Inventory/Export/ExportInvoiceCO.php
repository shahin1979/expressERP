<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Models\Inventory\Export\ExportContract;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportInvoiceCO extends Controller
{
    use CommonTrait, TransactionsTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,57020);

        $contracts = ExportContract::query()->where('company_id',$this->company_id)->pluck('export_contract_no','id');
        if(!empty($request['contract_id']))
        {
            $contract = ExportContract::query()->where('company_id',$this->company_id)
                ->where('id',$request['contract_id'])->first();

            $imp_banks = Bank::query()->where('company_id',$this->company_id)
                ->where('bank_type','I')
                ->select(DB::raw("CONCAT('bank_name',' : ', 'branch_name') AS display_name"),'id')
                ->pluck('display_name','id');

            $exp_banks = Bank::query()->where('company_id',$this->company_id)
                ->where('bank_type','M')
                ->select(DB::raw("CONCAT('bank_name',' : ', 'branch_name') AS display_name"),'id')
                ->pluck('display_name','id');

//            dd($contract);
            return view('inventory.export.index.export-invoice-index',compact('contract','imp_banks','exp_banks'));
        }

        return view('inventory.export.index.export-invoice-index',compact('contracts'));
    }
}
