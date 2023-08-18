<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221113
*/

namespace App\Http\Services;

use App\Imports\MaterialImport;
use App\Imports\ProcessImport;
use App\Models\MaterialRate;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessService {
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
            Storage::disk('public')->putFileAs('import/process', $file, $file_name);

            $import = Excel::toArray(new ProcessImport, 'uploads/import/process/' . $file_name);
        }

        if ($filename) {
            $import = Excel::toArray(new ProcessImport, 'uploads/import/process/' . $filename);
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
            return [
                'group' => $item[1],
                'code' => $item[2],
                'rate' => $item[3]
            ];
        })->values();
        $import = collect($import)->groupBy('group')->all();
        return [
            'filename' => $filename ?? $file_name,
            'data' => $import
        ];
    }
}