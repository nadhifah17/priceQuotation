<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\InvoicesTmminPerMonthSheet;


class SummaryTmminExport implements WithMultipleSheets
{
    use Exportable;

    protected $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        for ($type = 1; $type <= 4; $type++) {
            $sheets[] = new InvoicesTmminPerMonthSheet($type,$this->id);
        }

        return $sheets;
    }
}
