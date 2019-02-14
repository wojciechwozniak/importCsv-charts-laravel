<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\CsvData;
use App\Human;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        Human::query()->truncate();
        $path = $request->file('csv_file')->getRealPath();

        if ($request->has('header')) {
            $data = Excel::load($request->file('csv_file')->getRealPath(), function ($reader) {
            })->get();
            if (!empty($data) && $data->count()) {
                $data = $data->toArray();
                for ($i = 0; $i < count($data); $i++) {
                    $dataImported[] = $data[$i];
                }
            }
            Human::insert($dataImported);
            return redirect()->route('import.success');
        } else {
            $data = array_map('str_getcsv', file($path));
            if (count($data) > 0) {
                if ($request->has('header')) {
                    $csv_header_fields = [];
                    foreach ($data[0] as $key => $value) {
                        $csv_header_fields[] = $key;
                    }
                }
                $csv_data = array_slice($data, 0, 2);

                $csv_data_file = CsvData::create([
                    'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                    'csv_header' => $request->has('header'),
                    'csv_data' => json_encode($data)
                ]);
            } else {
                return redirect()->back();
            }
        }

        return view('import_fields', compact('csv_header_fields', 'csv_data', 'csv_data_file'));

    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $human = new Human();
            foreach ($request->fields as $index => $field) {

                if ($field != 'id') {
//                    dd($row);
                    $human->$field = $row[$index];
                }
            }
            $human->save();
        }

        return redirect()->route('import.success');
    }

    public function importSuccess()
    {
        $res = [];
        $data = Human::fromCountry();
        foreach ($data as $h) {
            $res['headers'][] = $h['country'];
            $res['data'][] = $h['national'];
        }
        return view('import_success', ['headers' => json_encode($res['headers']), 'data' => json_encode($res['data'])]);
    }
}
