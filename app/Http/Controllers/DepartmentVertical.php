<?php

namespace App\Http\Controllers;

use App\Models\Admin\master_vertical;
use Illuminate\Support\Facades\Http;

class DepartmentVertical extends Controller
{

    public function getAllVertical()
    {

        $vertical = master_vertical::with('company', 'department')->get();
        return datatables()->of($vertical)
            ->addIndexColumn()
            ->addColumn('company_code', function ($vertical) {
                return $vertical->company->company_code;
            })
            ->addColumn('DepartmentCode', function ($vertical) {
                return $vertical->department->DepartmentCode;
            })
            ->make(true);
    }

    public function syncVertical()
    {
        $query =  master_vertical::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Vertical')->json();
        $data = array();
        foreach ($response['vertical_list'] as $key => $value) {
            $temp = array();
            $temp['VerticalId'] = $value['VerticalId'];
            $temp['CompanyId'] = $value['ComId'];
            $temp['DepartmentId'] = $value['DeptId'];
            $temp['VerticalName'] = $value['VerticalName'];
            array_push($data, $temp);
        }
        $query = master_vertical::insert($data);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Vertical data has been Synchronized.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
}
