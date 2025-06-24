<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\master_specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;

class EduSpecialController extends Controller
{
    function eduspecialization()
    {
        $edu_list = DB::table("master_education")->where('Status', 'A')->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        return view('admin.eduspecialization', compact('edu_list'));
    }
    // ?===============Insert Education records in Database===================
    public function addEduSpe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Specialization' => 'required',
            'Education' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = array();
            foreach ($request->Education as $key => $value) {
                $temp = array();
                $temp['EducationId'] = $value;
                $temp['Specialization'] = $request->Specialization;
                $temp['Status'] = $request->Status;
                array_push($data, $temp);
            }
            $query = master_specialization::insert($data);

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'New Education Specialization has been successfully created.']);
            }
        }
    }

    // ?====================Get All Education Data From Datatabse=====================

    public function getAllEduSpe()
    {
       $Education= DB::table('master_specialization')->join('master_education', 'master_specialization.EducationId', '=', 'master_education.EducationId')
        ->select(['master_specialization.*', 'master_education.EducationCode']);

        return datatables()->of($Education)
            ->addIndexColumn()
            ->addColumn('actions', function ($Education) {
                return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $Education->SpId . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>  
           <button class="btn btn-sm btn btn-outline-danger font-13 delete" data-id="' . $Education->SpId . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // ?========================Get Education Details for Edit ========================//

    public function getEduSpeDetails(Request $request)
    {
        $SpId = $request->SpId;
        $EduSpeDetails = master_specialization::find($SpId);
        return response()->json(['EduSpeDetails' => $EduSpeDetails]);
    }

    // ?=====================Update Education Details===================
    public function editEduSpe(Request $request)
    {
        $SpId = $request->SpId;
        $validator = Validator::make($request->all(), [
            'editEduSpe' => 'required',
            'editEducation' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $Specialization = master_specialization::find($SpId);
            $Specialization->EducationId = $request->editEducation;
            $Specialization->Specialization = $request->editEduSpe;
            $Specialization->Status = $request->editStatus;
            $query = $Specialization->save();
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Education Specialization data has been changed successfully.']);
            }
        }
    }

    public function deleteEduSpe(Request $request)
    {
        $SpId = $request->SpId;
        $query = master_specialization::find($SpId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Specialization data has been Deleted.']);
        }
    }
}
