<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\SummaryExport;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = setPageTitle(__('view.cost_adm'));
        $title = __('view.cost_adm');
        return view('adminLte.pages.cost.index', compact('pageTitle', 'title'));
    }

    /**
     * Function to show data for datatables
     *
     * @return DataTables
     */
    public function ajax()
    {
        $data = Cost::all();
        return DataTables::of($data)
            ->editColumn('created_at', function ($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
            ->addColumn('action', function ($d) {
                // if (!is_null(DB::table('summary_cost')->where('cost_id',$d->id)->first())) {
                //     return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("cost.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>
                //     <a class="btn btn-sm bg-danger" type="button" href="'.route("cost.destroy", ['id'=>$d->id]).'">'. __('view.delete') .'</a>';
                // } else {
                //     return '<a class="btn btn-sm bg-primary-warning" type="button" href="'.route("cost.calculate", ['id'=>$d->id]).'">'. __('view.calculate') .'</a>';
                // }

                if (auth()->user()->can('manage-cost-adm')) {

                    if (!is_null(DB::table('summary_cost')->where('cost_id',$d->id)->first())) {
                        return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("cost.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
                    } else {
                        return '<a class="btn btn-sm bg-primary-warning" type="button" href="'.route("cost.calculate", ['id'=>$d->id]).'">'. __('view.calculate') .'</a>';
                    }

                } else {
                    return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("cost.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>
                    <a class="btn btn-sm bg-danger" type="button" href="'.route("cost.destroy", ['id'=>$d->id]).'">'. __('view.delete') .'</a>';
                }
            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            $model = new Cost();
            $model->name = $request->name;
            $model->number = $request->number;
            $model->save();

            return response()->json(['message' => 'Success create Cost']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cost = DB::table('cost')->where('id', $id)->first();
        $title = __('view.calculate');
        $mat = DB::table('material_cost')->where('cost_id',$cost->id)->get();
        $proc = DB::table('process_cost')->where('cost_id',$cost->id)->get();
        $pur = DB::table('purchase_cost')->where('cost_id',$cost->id)->get();
        return view('adminLte.pages.cost.editCost', ['title'=>$title,'cost'=>$cost,'mat'=>$mat,'proc'=>$proc,'pur'=>$pur]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            $data = Cost::find($id);
            $data->delete();
            return redirect()->route('cost.index')->with('success', 'Data has been deleted !');
    }

    public function calculate($id)
    {
        $cost = DB::table('cost')->where('id', $id)->first();
        $title = __('view.calculate');
        return view('adminLte.pages.cost.calculate', ['title'=>$title,'cost'=>$cost]);
    }

    public function downloadInvoices($id)
    {
        return (new SummaryExport($id))->download('cost.xlsx');
    }
}
