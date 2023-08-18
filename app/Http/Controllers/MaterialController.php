<?php

namespace App\Http\Controllers;

use App\DataTables\MaterialDataTable;
use App\Http\Services\MaterialService;
use App\Imports\MaterialImport;
use App\Models\Material;
use App\Models\MaterialRate;
use App\Models\MaterialSpec;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $pageTitle = setPageTitle(__('view.page_title', ['name' => 'Material ' . $type]));
        $title = __('view.material');
        return view('adminLte.pages.material.index', compact('type', 'pageTitle', 'title'));
    }

    /**
     * Function to get data for DataTables
     * @param string type
     * @return Response
     */
    public function ajax($type)
    {
        $material_type = Material::where('name', $type)->first();
        $material_type_id = $material_type->id;
        $data = MaterialRate::with(['material', 'materialSpec'])
            ->where('material_id', $material_type_id)
            ->get();
        return DataTables::of($data)
            ->editColumn('material_spec_id', function($d) {
                return $d->materialSpec ? $d->materialSpec->specification : '-';
            })
            ->editColumn('period', function($d) {
                return date('M y', strtotime($d->period));
            })
            ->editColumn('rate', function($d) {
                return number_format($d->rate);
            })
            ->addColumn('action', function($d) use($type) {
                return '<button class="btn btn-sm bg-primary-warning" data-url="'. route('material.edit', ['type' => $type, 'id' => $d->id]) .'" id="btn-edit-material-'. $d->id .'" type="button" data-type="'. $type .'" onclick="editItem('. $d->id .')">Edit</button>
                    <button class="btn btn-sm bg-primary-danger" type="button" onclick="deleteItem('. $d->id .')">Delete</button>';
            })
            ->rawColumns(['material_spec_id', 'period', 'rate', 'action'])
            ->make(true);
    }

    /**
     * Function to show list of current material code based on given key
     * @param string term
     * 
     */
    public function searchSpec(Request $request)
    {
        try {
            $term = $request->term;

            $data = MaterialSpec::select('specification', 'id')
                ->where('specification', 'LIKE', "%$term%")
                ->get();
            $data = collect($data)->map(function($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->specification
                ];
            })->values();

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to get data'], 500);
        }
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
    public function store(Request $request, $type)
    {
        DB::beginTransaction();
        try {
            // validation
            $validate = Validator::make($request->all(), [
                'spec' => 'required',
                'period' => 'required',
                'rate' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $material_type = Material::where('name', $type)->first();
            $material_type_id = $material_type->id;

            $spec = new MaterialSpec();
            $spec->material_id = $material_type_id;
            $spec->specification = $request->spec;
            $spec->save();

            $model = new MaterialRate();
            $model->material_id = $material_type_id;
            $model->material_spec_id = $spec->id;
            $model->period = date('Y-m-d', strtotime('01-' . $request->period));
            $model->rate = $request->rate;
            $model->save();

            DB::commit();
            return response()->json(['message' => 'Berhasil Menambah Data Material']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Function to retrieve all import data before save to database
     * @return Renderable
     */
    public function import(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'file' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $service = new MaterialService();
            $res = $service->read($request->file);
            if (isset($res['error'])) {
                return response()->json(['message' => $res['message']], 500);
            }
            $import = $res['data'];

            $view = view('adminLte.pages.material.import-overview', compact('import'))->render();
            return response()->json(['view' => $view, 'filename' => $res['filename']]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to process imported file and save to database
     * @param string filename
     * @return Response
     */
    public function submitImport(Request $request)
    {
        DB::beginTransaction();
        try {
            $filename = $request->current_file;
            $service = new MaterialService();
            $read = $service->read(null, $filename);
            if (isset($read['error'])) {
                return response()->json(['message' => $read['message']], 500);
            }
            $data = $read['data'];

            $material_rate_format = [];
            $a = 0;
            foreach ($data as $key => $item) {
                // find or create based on key
                $group = Material::where('name', $key)->first();
                if (!$group) {
                    $group = new Material();
                    $group->name = $key;
                    $group->save();
                }

                $b = 0;
                foreach ($item as $i) {
                    $spec = MaterialSpec::where('material_id', $group->id)
                        ->where('specification', $i['spec'])
                        ->first();
                    if (!$spec) {
                        $spec = MaterialSpec::create([
                            'material_id' => $group->id,
                            'specification' => $i['spec'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }

                    MaterialRate::updateOrCreate(
                        [
                            'material_id' => $group->id,
                            'material_spec_id' => $spec->id,
                            'period' => $i['period']
                        ],
                        [
                            'rate' => $i['rate']
                        ]
                    );

                    $b++;
                }
                $a++;
            }

            DB::commit();
            return response()->json(['message' => 'Success import data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Function to download material template
     * @return File
     */
    public function downloadTemplate()
    {
        try {
            return response()->download('material_template.xlsx');
        } catch (\Throwable $th) {
            return back()
                ->with(['error_message_alert' => 'Failed to download template']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit($type, $id)
    {
        $data = MaterialRate::select('id', 'material_id', 'material_spec_id', 'rate', 'period')
            ->with(['material', 'materialSpec'])
            ->find($id);
        
        $data->period = date('m-Y', strtotime($data->period));
        $url = route('material.update', ['id' => $id]);
        return response()->json(['data' => $data, 'url' => $url]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // validation
            $validate = Validator::make($request->all(), [
                'spec' => 'required',
                'period' => 'required',
                'rate' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $material = MaterialRate::find($id);
            $current_spec_id = $material->material_spec_id;

            $material->rate = $request->rate;
            $material->period = date('Y-m-d', strtotime('01-' . $request->period));
            $material->save();

            $spec = MaterialSpec::find($current_spec_id);
            $spec->specification = $request->spec;
            $spec->save();

            DB::commit();
            return response()->json(['message' => 'Berhasil Memperbarui Data Material']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $material = MaterialRate::find($id);
            $material_spec_id = $material->material_spec_id;
            $material->delete();

            $check = MaterialRate::select('id')
                ->where('material_spec_id', $material_spec_id)->count();
            if ($check == 0) {
                MaterialSpec::where('id', $material_spec_id)->delete();
            } DB::commit();
            return response()->json(['message' => 'Berhasil Menghapus Data Material']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()]);
        }
    }
}
