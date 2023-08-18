<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class InvoicesPerMonthSheet implements FromQuery, WithTitle, WithHeadings
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
            return DB::table('cost')->where('cost.id',$this->id)
            ->join('material_cost', 'cost.id', '=', 'material_cost.cost_id')
            ->join('materials', 'material_cost.material_group', '=', 'materials.id')
            ->join('material_specs', 'material_cost.spec', '=', 'material_specs.id')
            ->select('material_cost.part_no AS part_no', 'process_cost.part_name AS part_name','material_cost.currency_value AS currency', 'materials.name AS material_name', 'material_specs.specification AS specification', 'material_cost.period AS period', 'material_cost.material_rate AS material_rate ', 'material_cost.exchange_rate AS exchange_rate', 'material_cost.usage_part AS usage_part', 'material_cost.overhead AS overhead', 'material_cost.total AS total')
            ->orderBy('cost.created_at');
        } elseif ($this->type == 2) {
            return DB::table('cost')->where('cost.id',$this->id)
            ->join('process_cost', 'cost.id', '=', 'process_cost.cost_id')
            ->join('process', 'process_cost.process_group', '=', 'process.id')
            ->join('process_code', 'process_cost.process_code', '=', 'process_code.id')
            ->select('material_cost.part_no AS part_no', 'process_cost.part_name AS part_name', 'process.name AS process_name','process_code.name AS process_code', 'process_cost.process_rate AS process_rate ', 'process_cost.cycle_time AS cycle_time', 'process_cost.overhead AS overhead', 'process_cost.total AS total')
            ->orderBy('cost.created_at');
        } elseif ($this->type == 3) {
            return DB::table('cost')->where('cost.id',$this->id)
            ->join('purchase_cost', 'cost.id', '=', 'purchase_cost.cost_id')
            ->join('currency_group', 'purchase_cost.currency', '=', 'currency_group.id')
            ->select'material_cost.part_no AS part_no', 'process_cost.part_name AS part_name', 'currency_group.name AS purchase_name', 'purchase_cost.type_currency AS type_currency', 'purchase_cost.period AS period', 'purchase_cost.value_currency AS value_currency ', 'purchase_cost.cost AS cost', 'purchase_cost.quantity AS quantity', 'purchase_cost.overhead AS overhead', 'purchase_cost.total AS total')
            ->orderBy('cost.created_at');
        } elseif ($this->type == 4) {
            return DB::table('cost')->where('cost.id',$this->id)
            ->join('summary_cost', 'cost.id', '=', 'summary_cost.cost_id')

            ->select('summary_cost.part_no AS part_no', 'summary_cost.part_name AS part_name', 'summary_cost.material_cost AS material_cost', 'summary_cost.process_cost AS process_cost ', 'summary_cost.purchase_cost AS purchase_cost', 'summary_cost.total AS total')
            ->orderBy('cost.created_at');
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->type == 1) {
            return 'Material Cost';
        } elseif ($this->type == 2) {
            return 'Process Cost';
        } elseif ($this->type == 3) {
            return 'Purchase Cost';
        } elseif ($this->type == 4) {
            return 'Summary Cost';
        }
    }

    public function headings(): array {
        if ($this->type == 1) {
            return [
                "PART NO","PART NAME",'CURRENCY',"MATERIAL" ,"SPECIFICATION",'DATE','MATERIAL RATE','EXCHANGE RATE' ,'USAGE PART','OVERHEAD','TOTAL'
            ];
        } elseif ($this->type == 2) {
            return [
                "PART NO","PART NAME","PROCESS" ,"PROCESS CODE",'PROCESS RATE','CYCLE TIME','OVERHEAD','TOTAL'
            ];
        } elseif ($this->type == 3) {
            return [
                "PART NO","PART NAME","CURRENCY" ,"TYPE CURRENCY (1  : 2 NON )",'PERIOD','VALUE CURRENCY','COST','QUANTITY','OVERHEAD','TOTAL'
            ];
        } elseif ($this->type == 4) {
            return [
                "PART NO","PART NAME",'MATERIAL COST','PROCESS COST','PURCHASE COST','TOTAL'
            ];
        }

    }
}
