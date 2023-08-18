<?php

namespace App\Http\Controllers;

use App\Http\Services\ProcessService;
use App\Models\Process;
use App\Models\ProcessCode;
use App\Models\ProcessRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProcessRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = setPageTitle(__('view.process'));
        $title = __('view.process');
        $process_group = Process::all();
        return view('adminLte.pages.process.index', compact('pageTitle', 'title', 'process_group'));
    }

    /**
     * Function to get data for datatables
     */
    public function ajax()
    {
        $data = ProcessRate::orderBy('process_id', 'asc')->get();
        return DataTables::of($data)
            ->editColumn('process_code_id', function($d) {
                $name = $d->code ? $d->code->name : '-';
                return $name;
            })
            ->editColumn('process_id', function($d) {
                $name = $d->group ? $d->group->name : '-';
                return $name;
            })
            ->addColumn('action', function($d) {
                return '<button class="btn btn-sm bg-primary-warning" data-url="'. route('process.show', $d->id) .'" id="btn-edit-process-'. $d->id .'" type="button" onclick="editItem('. $d->id .')">Edit</button>
                    <button class="btn btn-sm bg-primary-danger" type="button" onclick="deleteItem('. $d->id .')">Delete</button>';
            })
            ->rawColumns(['action', 'process_code_id', 'process_id'])
            ->make(true);
    }

    /**
     * Function to show list of current code code based on given key
     * @param string term
     * 
     */
    public function searchSpec(Request $request)
    {
        try {
            $term = $request->term;

            $data = ProcessCode::select('name', 'id')
                ->where('name', 'LIKE', "%$term%")
                ->get();
            $data = collect($data)->map(function($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->name
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
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'process_group' => 'required',
                'process_code' => 'required',
                'rate' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $code = ProcessCode::where('name', $request->process_code)->first();
            if (!$code) {
                $code = new ProcessCode();
                $code->name = $request->process_code;
                $code->process_id = $request->process_group;
                $code->save();
            }

            $model = new ProcessRate();
            $model->process_id = $request->process_group;
            $model->process_code_id = $code->id;
            $model->rate = $request->rate;
            $model->save();

            return response()->json(['message' => 'Berhasil Menambah Data Process']);
        } catch (\Throwable $th) {
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
            return response()->download('process_template.xlsx');
        } catch (\Throwable $th) {
            return back()
                ->with(['error_message_alert' => 'Failed to download template']);
        }
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

            $service = new ProcessService();
            $res = $service->read($request->file);
            if (isset($res['error'])) {
                return response()->json(['message' => $res['message']], 500);
            }
            $import = $res['data'];

            $view = view('adminLte.pages.process.import-overview', compact('import'))->render();
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
            $service = new ProcessService();
            $read = $service->read(null, $filename);
            if (isset($read['error'])) {
                return response()->json(['message' => $read['message']], 500);
            }
            $data = $read['data'];
            $material_rate_format = [];
            $a = 0;
            foreach ($data as $key => $item) {
                // find or create based on key
                $group = Process::where('name', $key)->first();
                if (!$group) {
                    $group = new Process();
                    $group->name = $key;
                    $group->save();
                }

                $b = 0;
                foreach ($item as $i) {
                    $code = ProcessCode::where('process_id', $group->id)
                        ->where('name', $i['code'])
                        ->first();
                    if (!$code) {
                        $code = ProcessCode::create([
                            'process_id' => $group->id,
                            'name' => $i['code'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }

                    ProcessRate::updateOrCreate(
                        [
                            'process_id' => $group->id,
                            'process_code_id' => $code->id,
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
     * Display the specified resource.
     *
     * @param  \App\Models\ProcessRate  $processRate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ProcessRate::with(['group', 'code'])->find($id);
        return response()->json(['message' => 'Success get data', 'data' => $data, 'url' => route('process.update', $id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProcessRate  $processRate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProcessRate  $processRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validate = Validator::make($request->all(), [
                'process_group' => 'required',
                'process_code' => 'required',
                'rate' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = ProcessRate::with('code')->find($id);
            $current_code = $model->code;

            $code = ProcessCode::where('name', $request->process_code)->first();

            if ($current_code->name != $request->process_code) {
                if ($code) {
                    return response()->json(['message' => 'Process code already exist in database'], 500);
                }
            }

            if (!$code) {
                $code = new ProcessCode();
                $code->name = $request->process_code;
                $code->process_id = $request->process_group;
                $code->save();
            }

            if ($current_code->name != $request->process_code) {
                // delete current code
                ProcessCode::where('name', $current_code->name)->delete();
            }

            $model->process_id = $request->process_group;
            $model->process_code_id = $code->id;
            $model->rate = $request->rate;
            $model->save();

            DB::commit();
            return response()->json(['message' => 'Berhasil Memperbarui Data Process']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProcessRate  $processRate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = ProcessRate::find($id);
            $current_code = $data->process_code_id;

            ProcessCode::find($current_code)->delete();
            $data->delete();

            DB::commit();
            return response()->json(['message' => 'Berhasil Menghapus Data Process']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
