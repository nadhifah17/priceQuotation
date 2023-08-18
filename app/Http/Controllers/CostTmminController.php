<?php

namespace App\Http\Controllers;

use App\Models\CostTmmin;
use App\Models\Data;
// use App\Models\User;
// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
// use App\Exports\SummaryExport;
use App\Exports\SummaryTmminExport;

class CostTmminController extends Controller
{
    public function index()
    {
        $pageTitle = setPageTitle(__('view.cost_tmmin'));
        $title = __('view.cost_tmmin');
        return view('adminLte.pages.tmmin.index', compact('pageTitle', 'title'));
    }

    public function ajax()
    {
        $data = CostTmmin::all();
        return DataTables::of($data)
            ->editColumn('created_at', function ($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
            ->addColumn('action', function ($d) {

            if (auth()->user()->can('manage-cost-tmmin')) {

                if (!is_null(DB::table('summary_cost_tmmin')->where('cost_id',$d->id)->first())) {
                    //return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("tmmin.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
                     return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("tmmin.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
                } else {
                    return '<a class="btn btn-sm bg-primary-warning" type="button" href="'.route("tmmin.calculate", ['id'=>$d->id]).'">'. __('view.calculate') .'</a>';
                }

            } else {
                return '<a class="btn btn-sm bg-danger" type="button" href="'.route("tmmin.delete", ['id'=>$d->id]).'">'. __('view.delete') .'</a>
                <a class="btn btn-sm bg-primary-blue" type="button" href="'.route("tmmin.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
            }

            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'number' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = new CostTmmin();
            $model->name = $request->name;
            $model->number = $request->number;
            $model->save();

            return response()->json(['message' => 'Berhasil Menambah Cost']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        //
    }

    public function editTmmin($id)
    {
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $title = __('view.calculate');
        $mat = DB::table('material_cost_tmmin')->where('cost_id',$cost->id)->get();
        $proc = DB::table('process_cost_tmmin')->where('cost_id',$cost->id)->get();
        $pur = DB::table('purchase_cost_tmmin')->where('cost_id',$cost->id)->get();
        return view('adminLte.pages.tmmin.edit', ['title'=>$title,'cost'=>$cost,'mat'=>$mat,'proc'=>$proc,'pur'=>$pur]);
    }

    public function viewResult($id)
    {
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $title = __('view.calculate');
        $mat = DB::table('material_cost_tmmin')->where('cost_id',$cost->id)->get();
        $proc = DB::table('process_cost_tmmin')->where('cost_id',$cost->id)->get();
        $pur = DB::table('purchase_cost_tmmin')->where('cost_id',$cost->id)->get();
        $sum = DB::table('summary_cost_tmmin')->where('cost_id',$cost->id)->first();
        return view('adminLte.pages.tmmin.result', ['title'=>$title,'cost'=>$cost,'mat'=>$mat,'proc'=>$proc,'pur'=>$pur, 'sum'=>$sum]);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $data = CostTmmin::find($id);
        $data->delete();
        return redirect()->route('tmmin.index')->with('success', 'Data has been deleted !');
    }

    public function calculateTmmin($id)
    {
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $title = __('view.calculate');
        return view('adminLte.pages.tmmin.calculate', ['title'=>$title,'cost'=>$cost]);
    }

    public function downloadTmminInvoices($id)
    {
        return (new SummaryTmminExport($id))->download('costTmmin.xlsx');
    }
}
