<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculateController extends Controller
{
    public function index($id)
    {
        $parent = DB::table('cost')->where('id', $id)->first();
        return view('adminLte.pages.calculate.index', ['parent'=>$parent]);
    }

    public function submitCost(Request $request)
    {

        $mat_total = 0;
        $proc_total = 0;
        $pur_total = 0;
        $all_total = 0;

        if (isset($_GET['update'])) {
            DB::table('material_cost')->where('cost_id',$request->cost_id)->delete();
        DB::table('process_cost')->where('cost_id',$request->cost_id)->delete();
        DB::table('purchase_cost')->where('cost_id',$request->cost_id)->delete();

        }


        if (!is_null($request->material_cost)) {
            foreach ($request->material_cost as $key => $value) {
                $currency = explode('-', $value['currency']);

                DB::table('material_cost')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'currency'=>$value['currency'],
                    'currency_value'=>DB::table('currency_group')->where('id',$currency[1])->first()->name,
                    'material_group'=>$value['material_group'],
                    'spec'=>$value['spec'],
                    'period'=>date('Y-m-d',strtotime($value['period'])),
                    'material_rate'=>$value['material_rate'],
                    'exchange_rate'=>$value['exchange_rate'],
                    'usage_part'=>$value['usage_part'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);

                $mat_total += $value['subtotal'];
            }
        }

        if (!is_null($request->process_cost)) {
            foreach ($request->process_cost as $key => $value) {
                DB::table('process_cost')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'process_group'=>$value['process_group'],
                    'process_code'=>$value['procces_code'],
                    'process_rate'=>$value['process_rate'],
                    'cycle_time'=>$value['cycle_time'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);
                $proc_total += $value['subtotal'];
            }
        }

        if (!is_null($request->purchase_cost)) {

        foreach ($request->purchase_cost as $key => $value) {
            DB::table('purchase_cost')->insert([
                'cost_id'=>$request->cost_id,
                'part_no'=>$value['part_no'],
                'part_name'=>$value['part_name'],
                'currency'=>$value['currency'],
                'type_currency'=>$value['currency_type'],
                'period'=>date('Y-m-d',strtotime($value['period'])),
                'value_currency'=>$value['currency_value'],
                'cost'=>$value['cost'],
                'quantity'=>$value['quantity'],
                'overhead'=>$value['overhead'],
                'total'=>$value['subtotal'],
            ]);
            $pur_total += $value['subtotal'];
        }
        }

        DB::table('summary_cost')->updateOrInsert([
            'cost_id'=>$request->cost_id,
            'part_no'=>DB::table('cost')->where('id',$request->cost_id)->first()->number,
            'part_name'=>DB::table('cost')->where('id',$request->cost_id)->first()->name,
            'material_cost'=>$mat_total,
            'process_cost'=>$proc_total,
            'purchase_cost'=>$pur_total,

            'total'=>$mat_total + $proc_total + $pur_total,
        ],[
            'cost_id'=>$request->cost_id,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'success submit or update','status'=>'success'], 200);
    }

    public function getSpec($id)
    {
        return response()->json(DB::table('material_specs')->where('material_id', $id)->get(), 200);
    }

    public function getProcess($id)
    {
        return response()->json(DB::table('process_code')->where('process_id', $id)->get(), 200);
    }
    public function getMatExRate($date, $currency, $material, $spec)
    {
        $currency = explode('-', $currency);

        $material = DB::table('material_rate')->where(['material_id'=> $material,'material_spec_id'=>$spec])->where('period', $date.'-01')->first();

        $currency_value = DB::table('currency_value')->where(['currency_type_id'=> $currency[0],'currency_group_id'=>$currency[1]])->where('period', $date.'-01')->first();
        return response()->json(['material_rate'=>$material,'currency_value'=>$currency_value], 200);
    }

    public function getProcessRate($proccess, $proccess_code)
    {
        $proccess_rate = DB::table('process_rate')->where(['process_id'=> $proccess,'process_code_id'=>$proccess_code])->first();
        return response()->json(['proccess_rate'=>$proccess_rate], 200);
    }

    public function getTypeCurrency($currency)
    {
        $currency_value = DB::table('currency_value')->where(['currency_group_id'=>$currency])->groupBy('currency_type_id')->get();
        return response()->json($currency_value, 200);
    }
    public function getValueCurrency($currency, $type, $date)
    {


        $currency_value = DB::table('currency_value')->where(['currency_type_id'=> $type,'currency_group_id'=>$currency])->where('period', $date.'-01')->first();
        return response()->json(['currency_value'=>$currency_value], 200);
    }

    public function submitCostTmmin(Request $request)
    {
        $mat_total = 0;
        $proc_total = 0;
        $pur_total = 0;
        $all_total = 0;

        if (isset($_GET['update'])) {
            DB::table('material_cost_tmmin')->where('cost_id',$request->cost_id)->delete();
        DB::table('process_cost_tmmin')->where('cost_id',$request->cost_id)->delete();
        DB::table('purchase_cost_tmmin')->where('cost_id',$request->cost_id)->delete();

        }

        if (!is_null($request->material_cost)) {
            foreach ($request->material_cost as $key => $value) {
                $currency = explode('-', $value['currency']);
                DB::table('material_cost_tmmin')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'currency'=>$value['currency'],
                    'currency_value'=>DB::table('currency_group')->where('id',$currency[1])->first()->name,
                    'material_group'=>$value['material_group'],
                    'spec'=>$value['spec'],
                    'period'=>date('Y-m-d',strtotime($value['period'])),
                    'material_rate'=>$value['material_rate'],
                    'exchange_rate'=>$value['exchange_rate'],
                    'usage_part'=>$value['usage_part'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);

                $mat_total += $value['subtotal'];
            }
        }

        if (!is_null($request->process_cost)) {
            foreach ($request->process_cost as $key => $value) {
                DB::table('process_cost_tmmin')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'process_group'=>$value['process_group'],
                    'process_code'=>$value['procces_code'],
                    'process_rate'=>$value['process_rate'],
                    'cycle_time'=>$value['cycle_time'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);
                $proc_total += $value['subtotal'];
            }
        }

        if (!is_null($request->purchase_cost)) {

        foreach ($request->purchase_cost as $key => $value) {
            DB::table('purchase_cost_tmmin')->insert([
                'cost_id'=>$request->cost_id,
                'part_no'=>$value['part_no'],
                'part_name'=>$value['part_name'],
                'currency'=>$value['currency'],
                'type_currency'=>$value['currency_type'],
                'period'=>date('Y-m-d',strtotime($value['period'])),
                'value_currency'=>$value['currency_value'],
                'cost'=>$value['cost'],
                'quantity'=>$value['quantity'],
                'overhead'=>$value['overhead'],
                'total'=>$value['subtotal'],
            ]);
            $pur_total += $value['subtotal'];
        }
        }

        DB::table('summary_cost_tmmin')->updateOrInsert([
            'cost_id'=>$request->cost_id,
            'part_no'=>DB::table('cost_tmmin')->where('id',$request->cost_id)->first()->number,
            'part_name'=>DB::table('cost_tmmin')->where('id',$request->cost_id)->first()->name,
            'material_cost'=>$mat_total,
            'process_cost'=>$proc_total,
            'purchase_cost'=>$pur_total,

            'total'=>$mat_total + $proc_total + $pur_total,
        ],[
            'cost_id'=>$request->cost_id,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'success submit or update','status'=>'success'], 200);
    }


     public function submitCostSpk(Request $request)
    {
        $mat_total = 0;
        $proc_total = 0;
        $pur_total = 0;
        $all_total = 0;

        if (isset($_GET['update'])) {
            DB::table('material_cost_tmmin')->where('cost_id',$request->cost_id)->delete();
        DB::table('process_cost_tmmin')->where('cost_id',$request->cost_id)->delete();
        DB::table('purchase_cost_tmmin')->where('cost_id',$request->cost_id)->delete();

        }

        if (!is_null($request->material_cost)) {
            foreach ($request->material_cost as $key => $value) {
                $currency = explode('-', $value['currency']);
                DB::table('material_cost_tmmin')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'currency'=>$value['currency'],
                    'currency_value'=>DB::table('currency_group')->where('id',$currency[1])->first()->name,
                    'material_group'=>$value['material_group'],
                    'spec'=>$value['spec'],
                    'period'=>date('Y-m-d',strtotime($value['period'])),
                    'material_rate'=>$value['material_rate'],
                    'exchange_rate'=>$value['exchange_rate'],
                    'usage_part'=>$value['usage_part'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);

                $mat_total += $value['subtotal'];
            }
        }

        if (!is_null($request->process_cost)) {
            foreach ($request->process_cost as $key => $value) {
                DB::table('process_cost_tmmin')->insert([
                    'cost_id'=>$request->cost_id,
                    'part_no'=>$value['part_no'],
                    'part_name'=>$value['part_name'],
                    'process_group'=>$value['process_group'],
                    'process_code'=>$value['procces_code'],
                    'process_rate'=>$value['process_rate'],
                    'cycle_time'=>$value['cycle_time'],
                    'overhead'=>$value['overhead'],
                    'total'=>$value['subtotal'],
                ]);
                $proc_total += $value['subtotal'];
            }
        }

        if (!is_null($request->purchase_cost)) {

        foreach ($request->purchase_cost as $key => $value) {
            DB::table('purchase_cost_tmmin')->insert([
                'cost_id'=>$request->cost_id,
                'part_no'=>$value['part_no'],
                'part_name'=>$value['part_name'],
                'currency'=>$value['currency'],
                'type_currency'=>$value['currency_type'],
                'period'=>date('Y-m-d',strtotime($value['period'])),
                'value_currency'=>$value['currency_value'],
                'cost'=>$value['cost'],
                'quantity'=>$value['quantity'],
                'overhead'=>$value['overhead'],
                'total'=>$value['subtotal'],
            ]);
            $pur_total += $value['subtotal'];
        }
        }

        DB::table('summary_cost_tmmin')->updateOrInsert([
            'cost_id'=>$request->cost_id,
            'part_no'=>DB::table('cost_tmmin')->where('id',$request->cost_id)->first()->number,
            'part_name'=>DB::table('cost_tmmin')->where('id',$request->cost_id)->first()->name,
            'material_cost'=>$mat_total,
            'process_cost'=>$proc_total,
            'purchase_cost'=>$pur_total,

            'total'=>$mat_total + $proc_total + $pur_total,
        ],[
            'cost_id'=>$request->cost_id,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'success submit or update','status'=>'success'], 200);
    }
}
