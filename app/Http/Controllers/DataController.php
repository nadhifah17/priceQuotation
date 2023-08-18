<?php

namespace App\Http\Controllers;

use App\Models\CostTmmin;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class DataController extends Controller
{
    public function index()
    {
        $data = Data::all();
        $pageTitle = setPageTitle("PART COST");
        $title = "SPK";
        return view('adminLte.pages.data.index', compact('data','pageTitle','title'));
    }

    public function column($id)
    {
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $data = Data::where('cost_id',$cost->id)->first();
        $pageTitle = setPageTitle("Alternatif");

        $title = "Alternatif";
        return view('adminLte.pages.data.column', compact('title','cost','data','pageTitle'));
    }



    public function ajax($id)
    {
        ///edittttt heerreee
        //$cost = CostTmmin::findOrFail($id);
        //$data = Data::all();
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $data = Data::where('cost_id',$cost->id)->get();
        //$data = Data::where('cost_id', 10)->get();
        return DataTables::of($data)
            ->editColumn('created_at', function ($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
            ->addColumn('action', function ($d) {

            if (auth()->user()->can('manage-spk-tmmin')) {

                if (is_null(DB::table('data')->where('cost_id',$d->cost_id)->first())) {
                     return '<a class="btn btn-sm bg-danger" type="button" href="'.route("data.destroy", $d->id).'">'. __('view.delete') .'</a>
                <a class="btn btn-sm bg-primary-blue" type="button" href="'.route("tmmin.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
                } else {
                    return '<button class="btn btn-sm bg-primary-danger" type="button" onclick="deleteItem('. $d->id .')">Delete</button>
                    <button class="btn btn-sm bg-primary-warning" data-url="'. route('data.edit',  $d->id) .'" id="btn-edit-attribute-'. $d->id .'" type="button" onclick="editItem('. $d->id .')">Edit</button>';
                }

            } else {
                return '<a class="btn btn-sm bg-danger" type="button" href="'.route("tmmin.delete", ['id'=>$d->id]).'">'. __('view.delete') .'</a>
                <a class="btn btn-sm bg-primary-blue" type="button" href="'.route("tmmin.edit", ['id'=>$d->id]).'">'. __('view.edit') .'</a>';
            }

            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    public function bajax()
    {
        $data = CostTmmin::all();
        return DataTables::of($data)
            ->editColumn('created_at', function ($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
            ->addColumn('action', function ($d) {

            if (auth()->user()->can('manage-master-tmmin')) {

                if (!is_null(DB::table('data')->where('cost_id',$d->id)->first())) {
                    return '<a class="btn btn-sm bg-primary-blue" type="button" href="'.route("data.column", ['id'=>$d->id]).'">'. __('view.edit_spk') .'</a>';
                    
                } else {
                    return '<a class="btn btn-sm bg-primary-warning" type="button" href="'.route("data.column", ['id'=>$d->id]).'">Add Alt</a>';
                }
            }

            if (auth()->user()->can('manage-cost-tmmin')) {

                if (!is_null(DB::table('data')->where('cost_id',$d->id)->first())) {
                    return '<a class="btn btn-sm bg-primary-success" type="button" href="'.route("data.calculate", ['id'=>$d->id]).'">View SPK</a>';
                    
                } else {
                    return '<a class="btn btn-sm bg-primary-success" type="button" href="'.route("data.calculate", ['id'=>$d->id]).'">View SPK</a>';
                }
            }

            

            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    public function cajax($id)
    {
        ///edittttt heerreee
        $cost = DB::table('cost_tmmin')->where('id', $id)->first();
        $data = Data::where('cost_id',$cost->id)->get();
        return DataTables::of($data)
        ->editColumn('created_at', function ($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
        ->editColumn('total', function($d) {
            return $d->total;
        })

        ->rawColumns(['created_at', 'total'])
        ->make(true);
 
    }

    public function create()
    {
        $pageTitle = setPageTitle("bbyy");
        $title = "bbyyyy";
        return view('adminLte.pages.data.create', compact('pageTitle','title'));
    }


    public function store(Request $request,$id)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(),[
                'name' => 'required|max:255',
                'price' => 'required|numeric|min:0.05|max:0.15',
                'quality' => 'required|numeric|min:1|max:5',
                'service' => 'required|numeric|min:0',
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => 'Please enter the weight of the appropriate criteria'], 500);
            }

                    // Ambil data cost_tmmin yang sudah ada
            $costTmmin = CostTmmin::find($id);

            if (!$costTmmin) {
                return response()->json(['message' => 'CostTmmin not found'], 500);
            }

            $model = new Data();
            $model->cost_id = $costTmmin->id;
            $model->name = $request->name;
            $model->price = $request->price;
            $model->quality = $request->quality;
            $model->service = $request->service;
            $model->save();

            DB::commit();
            return response()->json(['message' => 'Berhasil Menambah Alternatif']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
            DB::rollBack();
        }

        //Data::create($validatedData);
    }

   public function show(Material $material)
    {
        //
    }

    public function edit($id)
    {
        //$data = Data::findOrFail($id);
        $data = Data::select('id','name', 'price', 'quality', 'service')
        ->find($id);
        $pageTitle = setPageTitle("Edit Data: $data->name");
        $title = "bbyyyy";

        $idPage = $id;
        $url = route('data.update',  $id);
        return response()->json(['data' => $data, 'url' => $url]);

        //return view('adminLte.pages.data.edit', compact('data','pageTitle','title'));
    }

    public function update(Request $request, $id)
    {
                DB::beginTransaction();
        try {
            // validation
            $validate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'price' => 'required|numeric|min:0.05|max:0.15',
                'quality' => 'required|numeric|min:1|max:5',
                'service' => 'required|numeric|min:0',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $material = Data::find($id);

            $material->name = $request->name;
            $material->price = $request->price;
            $material->quality = $request->quality;
            $material->service = $request->service;

            $material->save();


            DB::commit();
            return response()->json(['message' => 'Berhasil Memperbarui Data Alternatif']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()]);
        }

    }



    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = Data::find($id);
            $data->delete();

            DB::commit();
            return response()->json(['message' => 'Berhasil Menghapus Data Alternatif']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()]);
        }
    }

public function calculate(Request $request,$id)
{
    $pageTitle = setPageTitle("SAW Calculation Result");
    $title = "SAW Calculation";
    //$data = Data::all();
    $cost = DB::table('cost_tmmin')->where('id', $id)->first();
    $data = Data::where('cost_id',$cost->id)->get();
    //$data = Data::where('cost_id',$cost->id)->get();

    // Define the weight for each criterion
    $priceWeight = 0.4;
    $qualityWeight = 0.25;
    $serviceWeight = 0.35;

    // Find the max and min value for each criterion
    $maxPrice = $data->max('price');
    $minPrice = $data->min('price');
    $maxQuality = $data->max('quality');
    $minQuality = $data->min('quality');
    $maxService = $data->max('service');
    $minService = $data->min('service');

    // Normalize the data
    foreach ($data as $alternative) {
        ////////CHAT GPT METHOD
       // $price = ($alternative->price - $minPrice) / ($maxPrice - $minPrice);
       //$quality = ($alternative->quality - $minQuality) / ($maxQuality - $minQuality);
       //$service = ($alternative->service - $minService) / ($maxService - $minService);

       /////SAW PPT METHOD
        $price = ($alternative->price) / ($maxPrice);
        $quality = ($minQuality )/($alternative->quality );
        $service = ($minService )/($alternative->service );

        // Calculate the total value for the alternative
        $total = ($priceWeight * $price) + ($qualityWeight * $quality) + ($serviceWeight * $service);

        // Update the alternative with the calculated total value
        $alternative->total = $total;
        $alternative->save();
    }

    // Retrieve the alternatives with the calculated total value
    $alternatives = Data::where('cost_id',$cost->id)->get();

    // Sort the alternatives in descending order based on the total value
    $alternatives = $alternatives->sortByDesc('total');

    return view('adminLte.pages.data.calculate', compact('alternatives','cost','pageTitle','title'));
}

}
