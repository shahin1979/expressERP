<?php

namespace App\Http\Controllers\Projects\Basic;

use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class NewProjectsCO extends Controller
{
    public function index()
    {
        return view('projects.basic.new-project-index');
    }

    public function getProjectData()
    {
        $projects = Project::query()->where('company_id',$this->company_id);

        return DataTables::of($projects)
            ->addColumn('action', function ($projects) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$projects->id.'"  type="button" class="btn btn-view btn-sm btn-secondary"><i class="fa fa-open">View</i></button>
                    <button data-remote="edit/' . $projects->id . '" data-rowid="'. $projects->id . '"
                        data-name="'. $projects->name . '"
                        data-shortname="'. $projects->short_name . '"
                        data-code="'. $projects->department_code . '"
                        data-top="'. $projects->top_rank . '"
                        data-email="'. $projects->email . '"
                        data-description="'. $projects->description . '"
                        data-leave = "'. $projects->leave_steps . '"
                        type="button" href="#department-update-modal" data-target="#department-update-modal" data-toggle="modal" class="btn btn-sm btn-department-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    </div>
                    <button data-remote="delete/'.$projects->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Del</i></button>
                    ';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['start_date'] = Carbon::createFromFormat('d-m-Y',$request['start_date'])->format('Y-m-d');
        $request['end_date'] = Carbon::createFromFormat('d-m-Y',$request['end_date'])->format('Y-m-d');
        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;
        $request['status'] = 'P';

        DB::begintransaction();

        try {

            Project::query()->create($request->all());

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'New Project Added '], 200);
    }
}
