<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class InvoicesTmminPerMonthSheet implements FromQuery, WithTitle, WithHeadings
{
    private $type;
    private $id;


    public function __construct(int $type,int $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        if ($this->type == 1) {
            return DB::table('cost_tmmin')->where('cost_tmmin.id',$this->id)
            ->join('material_cost_tmmin', 'cost_tmmin.id', '=', 'material_cost_tmmin.cost_id')
            ->join('materials', 'material_cost_tmmin.material_group', '=', 'materials.id')
            ->join('material_specs', 'material_cost_tmmin.spec', '=', 'material_specs.id')
            ->select('material_cost_tmmin.part_no AS part_no', 'material_cost_tmmin.part_name AS part_name', 'material_cost_tmmin.currency_value AS currency','materials.name AS material_name', 'material_specs.specification AS specification', 'material_cost_tmmin.period AS period', 'material_cost_tmmin.material_rate AS material_rate ', 'material_cost_tmmin.exchange_rate AS exchange_rate', 'material_cost_tmmin.usage_part AS usage_part', 'material_cost_tmmin.overhead AS overhead', 'material_cost_tmmin.total AS total')
            ->orderBy('cost_tmmin.created_at');
        } elseif ($this->type == 2) {
            return DB::table('cost_tmmin')->where('cost_tmmin.id',$this->id)
            ->join('process_cost_tmmin', 'cost_tmmin.id', '=', 'process_cost_tmmin.cost_id')
            ->join('process', 'process_cost_tmmin.process_group', '=', 'process.id')
            ->join('process_code', 'process_cost_tmmin.process_code', '=', 'process_code.id')
            ->select('process_cost_tmmin.part_no AS part_no', 'process_cost_tmmin.part_name AS part_name', 'process.name AS process_name','process_code.name AS process_code', 'process_cost_tmmin.process_rate AS process_rate ', 'process_cost_tmmin.cycle_time AS cycle_time', 'process_cost_tmmin.overhead AS overhead', 'process_cost_tmmin.total AS total')
            ->orderBy('cost_tmmin.created_at');
        } elseif ($this->type == 3) {
            return DB::table('cost_tmmin')->where('cost_tmmin.id',$this->id)
            ->join('purchase_cost_tmmin', 'cost_tmmin.id', '=', 'purchase_cost_tmmin.cost_id')
            ->join('currency_group', 'purchase_cost_tmmin.currency', '=', 'currency_group.id')
            ->select('purchase_cost_tmmin.part_no AS part_no', 'purchase_cost_tmmin.part_name AS part_name', 'currency_group.name AS purchase_name', 'purchase_cost_tmmin.type_currency AS type_currency', 'purchase_cost_tmmin.period AS period', 'purchase_cost_tmmin.value_currency AS value_currency ', 'purchase_cost_tmmin.cost AS cost', 'purchase_cost_tmmin.quantity AS quantity', 'purchase_cost_tmmin.overhead AS overhead', 'purchase_cost_tmmin.total AS total')
            ->orderBy('cost_tmmin.created_at');
        } elseif ($this->type == 4) {
            return DB::table('cost_tmmin')->where('cost_tmmin.id',$this->id)
            ->join('summary_cost_tmmin', 'cost_tmmin.id', '=', 'summary_cost_tmmin.cost_id')

            ->select('summary_cost_tmmin.part_no AS part_no', 'summary_cost_tmmin.part_name AS part_name', 'summary_cost_tmmin.material_cost AS material_cost', 'summary_cost_tmmin.process_cost AS process_cost ', 'summary_cost_tmmin.purchase_cost AS purchase_cost', 'summary_cost_tmmin.total AS total')
            ->orderBy('cost_tmmin.created_at');
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->type == 1) {
            return 'Material Cost Tmmin';
        } elseif ($this->type == 2) {
            return 'Process Cost Tmmin';
        } elseif ($this->type == 3) {
            return 'Purchase Cost Tmmin';
        } elseif ($this->type == 4) {
            return 'Summary Cost Tmmin';
        }
    }

    public function headings(): array {
        if ($this->type == 1) {
            return [
                "Child Part No.","Child Part Name",'Currency',"Material" ,"Spec",'Period','Material Rate','Exchange RATE' ,'Usage Part','Overhead','Total Material'
            ];
        } elseif ($this->type == 2) {
            return [
                "Child Part No.","Child Part Name","Process" ,"Process Code",'Process Rate','Cycle Time','Overhead','Total Process'
            ];
        } elseif ($this->type == 3) {
            return [
                "Child Part No.","Child Part Name","Currency" ,"Type Currency(1 SLIDE:2 NON SLIDE)",'Period','Value Currency','Cost','Quantity','Overhead','Total Purchased'
            ];
        } elseif ($this->type == 4) {
            return [
                 "Mother Part No.","Mother Part Name",'Material Cost','Process Cost','Purchase cOST','TOTAL'
            ];
        }

    }
}
