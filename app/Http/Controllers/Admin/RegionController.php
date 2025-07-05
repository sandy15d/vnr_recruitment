<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Hq_Vertical_Region;
use App\Models\Admin\master_department;
use App\Models\Admin\master_region;
use App\Models\Admin\master_zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    public function region()
    {
        return view('admin.region');
    }


    public function syncRegion()
    {


        master_zone::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=zone')->json();
        $data = array();
        foreach ($response['zone_list'] as $key => $value) {

            $temp = array();
            $temp['ZoneId'] = $value['ZoneId'];
            $temp['ZoneName'] = $value['ZoneName'];
            array_push($data, $temp);
        }
        master_zone::insert($data);
        master_region::truncate();
        $response1 = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=region')->json();
        $data1 = array();
        foreach ($response1['region_list'] as $key => $value) {

            $temp = array();
            $temp['ZoneId'] = $value['ZoneId'];
            $temp['RegionId'] = $value['RegionId'];
            $temp['RegionName'] = $value['RegionName'];
            $temp['Status'] = $value['sts'];
            array_push($data1, $temp);
        }
        $query = master_region::insert($data1);

        if ($query) {

            return response()->json(['status' => 200, 'msg' => 'Region data has been Synchronized.']);
        } else {

            return response()->json(['status' => 500, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function getAllZone()
    {
        $zone = master_zone::all();
        return datatables()->of($zone)
            ->addIndexColumn()
            ->make(true);
    }

    public function getAllRegion()
    {
        $region = DB::table('master_region')->leftJoin('master_zone', 'master_region.ZoneId', '=', 'master_zone.ZoneId')->select('master_region.*', 'master_zone.ZoneName')->get();
        return datatables()->of($region)
            ->addIndexColumn()
            ->editColumn('Status', function ($region) {
                if ($region->Status == 'A') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->rawColumns(['Status'])
            ->make(true);
    }


    public function hq_wise_region()
    {
        $department_list = master_department::where([['DeptStatus', 'A'], ['CompanyId', session('Set_Company')]])->pluck("DepartmentName", "DepartmentId");
        return view('admin.hq_wise_region', compact('department_list'));
    }

    public function syncHqRegion()
    {
        Hq_Vertical_Region::truncate();
        $response1 = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=hq_region')->json();
        $data1 = array();
        foreach ($response1['region_list'] as $key => $value) {

            $temp = array();
            $temp['VHqId'] = $value['VHqId'];
            $temp['Vertical'] = $value['Vertical'];
            $temp['HqId'] = $value['HqId'];
            $temp['RegionId'] = $value['RegionId'];
            $temp['DeptId'] = $value['DeptId'];
            $temp['CompanyId'] = $value['CompanyId'];
            $temp['Status'] = $value['Status'];
            array_push($data1, $temp);
        }
        $query = Hq_Vertical_Region::insert($data1);

        if ($query) {

            return response()->json(['status' => 200, 'msg' => 'Region data has been Synchronized.']);
        } else {

            return response()->json(['status' => 500, 'msg' => 'Something went wrong..!!']);
        }
    }


    public function getAllRegionHq(Request $request)
    {
        $usersQuery = DB::table('master_hq_vertical_region');
        $Department = $request->Department;
        if ($Department != '') {
            $usersQuery->where("master_hq_vertical_region.DeptId", $Department);
        }
        $sql = $usersQuery->leftJoin('master_headquater', 'master_headquater.HqId', '=', 'master_hq_vertical_region.HqId')
            ->join('master_vertical', 'master_vertical.VerticalId', '=', 'master_hq_vertical_region.Vertical')
            ->leftJoin('master_region', 'master_region.RegionId', '=', 'master_hq_vertical_region.RegionId')
            ->join('master_department', 'master_department.DepartmentId', '=', 'master_hq_vertical_region.DeptId')
            ->leftJoin('master_zone', 'master_zone.ZoneId', '=', 'master_region.ZoneId')
            ->select('master_hq_vertical_region.*', 'master_headquater.HqName', 'master_vertical.VerticalName', 'master_region.RegionName', 'master_department.DepartmentName', 'master_zone.ZoneName')
            ->where('master_hq_vertical_region.CompanyId', session('Set_Company'))
            ->groupBy('master_hq_vertical_region.HqId')
            ->groupBy('master_hq_vertical_region.RegionId')
            ->groupBy('master_hq_vertical_region.Vertical')
            ->get();

        return datatables()->of($sql)
            ->addIndexColumn()
            ->editColumn('Status', function ($sql) {
                if ($sql->Status == 'A') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->rawColumns(['Status'])
            ->make(true);
    }

    public function getRegionByVertical(Request $request)
    {
        $Department = $request->Department;
        $Vertical = $request->Vertical;
        $HqId = $request->HqId;
        $region_list = DB::table('master_hq_vertical_region')->leftJoin('master_region', 'master_region.RegionId', '=', 'master_hq_vertical_region.RegionId')
            ->leftJoin('master_zone', 'master_zone.ZoneId', '=', 'master_region.ZoneId')
            ->select('master_region.RegionId', 'master_region.RegionName', 'master_zone.ZoneId', 'master_zone.ZoneName')
            ->where("master_hq_vertical_region.DeptId", $Department)
            ->where("master_hq_vertical_region.Vertical", $Vertical)
            ->where("master_hq_vertical_region.HqId", $HqId)
            ->where('master_region.Status', 'A')
            ->groupBy('master_hq_vertical_region.HqId')
            ->groupBy('master_hq_vertical_region.RegionId')
            ->groupBy('master_hq_vertical_region.Vertical')

            ->get();
        return response()->json(['status' => 200, 'region_list' => $region_list]);
    }
}
