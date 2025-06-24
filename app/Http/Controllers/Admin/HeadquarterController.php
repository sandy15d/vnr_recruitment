<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\master_headquarter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use DataTables;

class HeadquarterController extends Controller
{
    public function headquarter()
    {
        return view('admin.headquarter');
    }
    public function getAllHeadquarter()
    {


        $headquarter = DB::table('master_headquater')
            ->join('core_company', 'master_headquater.CompanyId', '=', 'core_company.id')
            ->join('master_state', 'master_headquater.StateId', '=', 'master_state.StateId')
            ->where('master_state.Country', '=', session('Set_Country'))
            ->where('master_headquater.CompanyId', '=', session('Set_Company'))
            ->select(['master_headquater.*', 'core_company.company_code', 'master_state.StateName']);


        return datatables()->of($headquarter)
            ->addIndexColumn()
            ->make(true);
    }

    public function syncHeadquarter()
    {

        $query =  master_headquarter::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Headquarter')->json();
        $data = array();
        foreach ($response['Headquarter_list'] as $key => $value) {

            $temp = array();
            $temp['HqId'] = $value['HqId'];
            $temp['HqName'] = $value['HqName'];
            $temp['StateId'] = $value['StateId'];
            $temp['CompanyId'] = $value['CompanyId'];
            array_push($data, $temp);
        }
        $query = master_headquarter::insert($data);


        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Headquarter data has been Synchronized.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
}
