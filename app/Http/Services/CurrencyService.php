<?php

namespace App\Http\Services;

use App\Imports\CurrencyImport;
use App\Models\CurrencyGroup;
use App\Models\CurrencyValue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CurrencyService {
    /**
     * Function to get list of currency data
     * @param string type
     * @param string group
     */
    public function list($type, $group)
    {
        try {
            // get id of type and group

        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Funciton to get id of given type
     * @param string $type
     * @return void
     */
    public function getTypeId($type)
    {
        $types = CurrencyValue::TYPES;
        return collect($types)->filter(function($item) use ($type) {
            return $item == $type;
        })->map(function($i) {
            $var = null;
            if ($i == CurrencyValue::SLIDE_TEXT) {
                $var = CurrencyValue::SLIDE_TYPE;
            } else if ($i == CurrencyValue::NON_SLIDE_TEXT) {
                $var = CurrencyValue::NON_SLIDE_TYPE;
            }
            return $var;
        })->values()[0];
    }

    /**
     * Funciton to get id of given group
     * @param string group
     * @return void
     */
    public function getGroupId($group)
    {
        $groups = CurrencyValue::GROUP;
        $text = collect($groups)->filter(function($item) use ($group) {
            return strtolower($item) == strtolower($group);
        })->values()[0];
        $data = CurrencyGroup::where('name', $text)->first();
        return $data ? $data->id : null;
    }

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
            Storage::disk('public')->putFileAs('import/currency', $file, $file_name);

            $import = Excel::toArray(new CurrencyImport, 'uploads/import/currency/' . $file_name);
        }

        if ($filename) {
            $import = Excel::toArray(new CurrencyImport, 'uploads/import/currency/' . $filename);
        }
        
        if (!$import) {
            return [
                'error' => true,
                'message' => "Cannot find the file"
            ];
        }

        // validation template
        if (
            $import[0][0][0] != CurrencyValue::KEY_TEMPLATE_1 &&
            $import[0][1][0] != CurrencyValue::KEY_TEMPLATE_2
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
        unset($import[4]);
        unset($import[5]);
        unset($import[6]);
        $import = array_values($import);
        $import = collect($import)->map(function($item) {
            $period = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[3]);
            $period = $period->format('Y-m-d');
            return [
                'group' => $item[2],
                'period' => $period,
                'value' => $item[4]
            ];
        })->values();
        $import = collect($import)->groupBy('group')->all();
        return [
            'filename' => $filename ?? $file_name,
            'data' => $import
        ];
    }
}