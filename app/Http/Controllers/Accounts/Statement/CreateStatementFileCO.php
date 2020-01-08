<?php

namespace App\Http\Controllers\Accounts\Statement;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Statement\StmtList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CreateStatementFileCO extends Controller
{
    public function index()
    {
        $fileList = StmtList::query()->where('company_id',$this->company_id)
            ->pluck('file_desc','file_no');

        return view('accounts.statement.statement-file-index',compact('fileList'));
    }

    public function getStatementFileList()
    {
        $statements = StmtList::query()->where('company_id',$this->company_id)->get();

        return DataTables::of($statements)

            ->addColumn('action', function ($statements) {

                return '<button data-rowId="'.$statements->id.'"
                                data-file="'.$statements->file_no.'"
                                data-name="'.$statements->file_desc.'"
                                data-fImport="'.$statements->import_file.'"
                                data-fLine="'.$statements->import_line.'"
                                data-tLine="'.$statements->into_line.'"
                    type="button" class="btn btn-statement-edit btn-xs btn-primary">Edit</button>
                <button data-remote="statement/delete/'.$statements->id.'"  type="button" class="btn btn-delete btn-xs btn-danger pull-right">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id']= $this->company_id;
        $request['user_id'] = $this->user_id;

        DB::beginTransaction();

        try {

            StmtList::query()->create($request->all());

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with(['error'=>$error]);
        }

        DB::commit();

        return redirect()->action('Accounts\Statement\CreateStatementFileCO@index')->with('success','Statement File Created');
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
