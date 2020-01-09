<?php

namespace App\Http\Controllers\Accounts\Statement;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Common\AccountType;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class CreateStatementLineCO extends Controller
{
    public function index(Request $request)
    {
        $fileList = StmtList::query()->where('company_id',$this->company_id)
            ->pluck('file_desc','file_no');

        $types = AccountType::query()->pluck('description','id');


        $param = collect();

        if(!empty($request['input_file']))
        {
            $param['file_no'] = $request['input_file'];
            $file = StmtList::query()->where('company_id',$this->company_id)
                ->where('file_no',$request['input_file'])->first();

            $maxLineNo = StmtLine::query()->where('company_id',$this->company_id)
                ->where('file_no',$request['input_file'])
                ->max('line_no');

            $lineNo = empty($maxLineNo) ? 5 : $maxLineNo + 5;
            $param['max_line'] = $lineNo;
            $param['file_desc'] = $file->file_desc;

//            dd($param);


        }

        return view('accounts.statement.statement-line-index',compact('fileList','param','types'));
    }

    public function getStatementLineData($id)
    {
//        $fileNo = Session::get('STATEMENT_FILE_NO');

        $lines = StmtLine::query()->where('company_id',$this->company_id)
            ->where('file_no',$id)->with('name')->get();

        return DataTables::of($lines)

            ->addColumn('action', function ($lines) {

                return '<button data-rowId="'.$lines->id.'"
                                data-file="'.$lines->file_no.'"
                                data-name="'.$lines->name->file_desc.'"
                                data-line="'.$lines->line_no.'"
                                data-textPosition="'.$lines->text_position.'"
                                data-fontSize="'.$lines->font_size.'"
                                data-texts="'.$lines->texts.'"
                                data-accType="'.$lines->acc_type.'"
                                data-note="'.$lines->note.'"
                                data-ac11="'.$lines->ac11.'"
                                data-ac12="'.$lines->ac12.'"
                                data-ac21="'.$lines->ac21.'"
                                data-ac22="'.$lines->ac22.'"
                                data-subTotal="'.$lines->sub_total.'"
                                data-formula="'.$lines->formula.'"
                    type="button" class="btn btn-statement-edit btn-xs btn-primary">Edit</button>
                <button data-remote="statement/delete/'.$lines->id.'"  type="button" class="btn btn-delete btn-xs btn-danger pull-right">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id']= $this->company_id;
        $request['user_id'] = $this->user_id;
        $request['figure_position'] = 60;

        DB::beginTransaction();

        try {

            StmtLine::query()->create($request->all());

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with(['error'=>$error]);
        }

        DB::commit();

        return redirect()->action('Accounts\Statement\CreateStatementLineCO@index',['input_file' => $request['file_no']])->with('success','Statement Line Created');
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            StmtList::query()->where('company_id',$this->company_id)
                ->where('file_no',$request['file_no'])
                ->update([
                    'file_desc'=>$request['file_desc'],
                    'import_file'=>$request['import_file'],'import_line'=>$request['import_line'],
                    'into_line'=>$request['into_line']
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Statement File Updated Successfully'],200);
    }
}
