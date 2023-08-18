<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221113
*/

namespace App\Http\Services;

use App\Imports\MaterialImport;
use App\Models\MaterialRate;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MaterialService {
    /**
     * Function to read an excel
     * @param file $file
     * @param string $filename
     * @return void
     */
    public function read($file = null, $filename = null) {
        $import = null;

        if ($file) {
            $file_name = 'import_' . date('YmdHis') . '.' . $file->extension();
            Storage::disk('public')->putFileAs('import/material', $file, $file_name);

            $import = Excel::toArray(new MaterialImport, 'uploads/import/material/' . $file_name);
        }

        if ($filename) {
            $import = Excel::toArray(new MaterialImport, 'uploads/import/material/' . $filename);
        }
        
        if (!$import) {
            return [
                'error' => true,
                'message' => "Cannot find the file"
            ];
        }

        // validation template
        if (
            $import[0][0][0] != MaterialRate::KEY_TEMPLATE_1 &&
            $import[0][1][0] != MaterialRate::KEY_TEMPLATE_2
        ) {
            return [
                'error' => true, 
                'message' => "Please use the template that has been provided"
            ];
        }

        $import = $import[0];
        unset($import[0]);
        unset($import[1]);
        unset($import[2]);
        unset($import[3]);
        $import = array_values($import);
        $import = collect($import)->map(function($item) {
            $period = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[3]);
            $period = $period->format('Y-m-d');
            return [
                'group' => $item[1],
                'spec' => $item[2],
                'period' => $period,
                'rate' => $item[4]
            ];
        })->values();
        $import = collect($import)->groupBy('group')->all();

        return [
            'filename' => $filename ?? $file_name,
            'data' => $import
        ];
    }
}