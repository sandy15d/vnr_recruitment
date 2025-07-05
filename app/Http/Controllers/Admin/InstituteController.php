<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\master_institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;

class InstituteController extends Controller
{
    public function institute()
    {
        $state_list = DB::table("states")->where('CountryId', session('Set_Country'))->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $university_list = master_institute::where('Institute_Type', 'Board/University')->get();
        return view('admin.institute', compact('state_list', 'district_list', 'university_list'));
    }


    // ?===============Insert Institute records in Database===================
    public function addInstitute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'InstituteName' => 'required',
            'InstituteCode' => 'required',
            'State' => 'required',
            'District' => 'required',
            'Category' => 'required',
            'Type' => 'required',
            'Institute_Type' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $Institute = new master_institute;
            $Institute->InstituteName = $request->InstituteName;
            $Institute->InstituteCode = $request->InstituteCode;
            $Institute->StateId = $request->State;
            $Institute->DistrictId = $request->District;
            $Institute->Category = $request->Category;
            $Institute->Type = $request->Type;
            $Institute->Institute_Type = $request->Institute_Type;
            $Institute->ParentId = $request->ParentId;
            $Institute->Status = $request->Status;
            $query = $Institute->save();

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'New Institute has been successfully created.']);
            }
        }
    }

    // ?====================Get All Education Data From Datatabse=====================

    public function getAllInstitute()
    {
        $Institute = DB::table('master_institute')->join('states', 'states.StateId', '=', 'master_institute.StateId')
            ->join('master_district', 'master_district.DistrictId', '=', 'master_institute.DistrictId')
            ->join('core_country', 'core_country.id', '=', 'states.CountryId')
            ->where('core_country.id', '=', session('Set_Country'))
            ->select(['master_institute.*', 'states.StateCode', 'master_district.DistrictName']);

        return datatables()->of($Institute)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('actions', function ($Institute) {
                return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $Institute->InstituteId . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>
                <button class="btn btn-sm btn btn-outline-danger font-13 delete" data-id="' . $Institute->InstituteId . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
            })
            ->rawColumns(['chk', 'actions'])
            ->make(true);
    }

    // ?========================Get Education Details for Edit ========================//

    public function getInstituteDetails(Request $request)
    {
        $InstituteId = $request->InstituteId;
        $IntituteDetails = master_institute::find($InstituteId);
        return response()->json(['IntituteDetails' => $IntituteDetails]);
    }

    // ?=====================Update Education Details===================
    public function editInstitute(Request $request)
    {
        $InstituteId = $request->EId;
        $validator = Validator::make($request->all(), [
            'editInstituteName' => 'required',
            'editInstituteCode' => 'required',
            'editState' => 'required',
            'editDistrict' => 'required',
            'editCategory' => 'required',
            'editType' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $Institute = master_institute::find($InstituteId);
            $Institute->InstituteName = $request->editInstituteName;
            $Institute->InstituteCode = $request->editInstituteCode;
            $Institute->StateId = $request->editState;
            $Institute->DistrictId = $request->editDistrict;
            $Institute->Category = $request->editCategory;
            $Institute->Type = $request->editType;
            $Institute->Status = $request->editStatus;
            $Institute->Institute_Type = $request->editInstitute_Type;
            $Institute->ParentId = $request->editParentId;
            $query = $Institute->save();
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Institute data has been changed successfully.']);
            }
        }
    }

    public function deleteInstitute(Request $request)
    {
        $InstituteId = $request->InstituteId;
        $query = master_institute::find($InstituteId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Institute data has been Deleted.']);
        }
    }
}
