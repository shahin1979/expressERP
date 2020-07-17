<?php

namespace App\Http\Controllers\Accounts\Setup;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Setup\Bank;
use App\Models\Administration\Organization\AdminDivision;
use App\Models\Security\UserPrivilege;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BankInformationCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        // Insert User Activity Log Table
        $this->menu_log($this->company_id,42015);
        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->whereIn('acc_type',['A','L'])->where('is_group',false)
            ->orderBy('acc_name')
            ->pluck('acc_name','id')
            ->prepend('No Account',null);

        return view('accounts.setup.bank-information-index',compact('ledgers'));
    }

    public function getBanks()
    {
        $banks = Bank::query()->where('status',true)
            ->where('company_id',$this->company_id)->with('account');

        return DataTables::of($banks)

            ->addColumn('action', function ($banks) {
                $permission = UserPrivilege::query()->where('user_id',$this->user_id)
                    ->where('menu_id',42015)->first();

                return '<div class="btn-group-sm" role="group" aria-label="Action Button">
                    <button data-rowid="'. $banks->id . '"
                        data-name="'. $banks->bank_name . '"
                        data-type="'. $banks->bank_type . '"
                        data-branch="'. $banks->branch_name . '"
                        data-account="'. $banks->bank_acc_no . '"
                        data-title="'. $banks->bank_acc_name . '"
                        data-ledger="'. $banks->related_gl_id . '"
                        data-email="'. $banks->email . '"
                        data-swift="'. $banks->swift_code . '"
                        data-mobile="'. $banks->mobile_no . '"
                        data-address="'. $banks->address . '"
                        data-status="'. $banks->status . '"
                        data-permission="'. $permission->edit . '"
                        type="button" class="btn btn-sm btn-bank-edit btn-primary" ><i class="fa fa-edit" >Edit</i></button>
                        <button data-permission="'. $permission->delete . '" data-remote="delete/'.$banks->id.'"  type="button" class="btn btn-bank-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;

        DB::begintransaction();

        try {

            Bank::query()->create($request->all());

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Failed to save Bank: '.$error);;
        }

        DB::commit();

        return redirect()->action('Accounts\Setup\BankInformationCO@index')->with('success','Successfully Saved Bank');

    }

    public function update(Request $request, $id)
    {
        $bank = Bank::query()->find($id);

        $bank->bank_type = $request['bank_type'];
        $bank->bank_name = $request['bank_name'];
        $bank->branch_name = $request['branch_name'];
        $bank->bank_acc_name = $request['bank_acc_name'];
        $bank->bank_acc_no = $request['bank_acc_no'];
        $bank->related_gl_id = $request['related_gl_id'];
        $bank->address = $request['address'];
        $bank->swift_code = $request['swift_code'];
        $bank->mobile_no = $request['mobile_no'];
        $bank->email = $request['email'];


        DB::beginTransaction();

        try{

            $bank->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'Bank Successfully Updated'], 200);
    }


    public function destroy($id)
    {
        $bank = Bank::query()->find($id);

        if($bank->status == true)
        {
            return response()->json(['error' => 'Bank is Active. Not Possible To Delete'], 404);
        }

        DB::beginTransaction();

        try{

            $bank->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'Bank Successfully Deleted '], 200);
    }

    public function printDivision()
    {
        $report = Bank::query()->where('company_id',$this->company_id)
            ->where('status',true)->get();

        $view = \View::make('administration.organization.pdf.pdf-division-list',compact('report'));

        $html = $view->render();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf::SetMargins(15, 5, 10,10);

        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('Divisions.pdf');


        return;
    }
}
