<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MW;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MinimumWageController extends Controller
{
    public function minimum_wage()
    {
        return view('admin.minimum_wage');
    }

    public function syncMW()
    {

        MW::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=minimum_wage')->json();
        $data = array();
        foreach ($response['minimum_wage_list'] as $value) {

            $temp = array();
            $temp['BWageId'] = $value['BWageId'];
            $temp['YearId'] = $value['YearId'];
            $temp['CompanyId'] = $value['CompanyId'];
            $temp['Category'] = $value['Category'];
            $temp['PerDayApr'] = $value['PerDayApr'];
            $temp['PerMonthApr'] = $value['PerMonthApr'];
            $temp['PerDayOct'] = $value['PerDayOct'];
            $temp['PerMonthOct'] = $value['PerMonthOct'];
            $temp['BWageStatus'] = $value['BWageStatus'];
            $temp['CrBy'] = $value['CrBy'];
            $temp['CrDate'] = $value['CrDate'];
            $temp['UpdBy'] = $value['UpdBy'];
            $temp['UpdDate'] = $value['UpdDate'];
            $data[] = $temp;
        }
        $query = MW::insert($data);


        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Grade data has been Synchronized.']);
        }

        return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
    }

    public function getAllMW()
    {
        $list = DB::table('minimum_wage_master')
            ->join('core_company', 'minimum_wage_master.CompanyId', '=', 'core_company.id')
            ->where('minimum_wage_master.CompanyId', '=', session('Set_Company'))
            ->whereIn('BWageId',[1, 2, 3, 4])
            ->orderBy('BWId','desc')
            ->limit(4)
            ->select(['minimum_wage_master.*']);

        return datatables()->of($list)
            ->addIndexColumn()
            ->make(true);
    }
}
