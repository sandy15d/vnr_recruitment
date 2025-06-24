<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin\master_education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class EducationController extends Controller
{
    public function education()
    {
        return view('admin.education');
    }

    // ?===============Insert Education records in Database===================
    public function addEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'EducationName' => 'required',
            'EducationCode' => 'required',
            'EducationType' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $Education = new master_education;
            $Education->EducationName = $request->EducationName;
            $Education->EducationCode = $request->EducationCode;
            $Education->EducationType = $request->EducationType;
            $Education->Status = $request->Status;
            $query = $Education->save();

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'New Education has been successfully created.']);
            }
        }
    }

    // ?====================Get All Education Data From Datatabse=====================

    public function getAllEducation()
    {
        $Education = master_education::all();

        return datatables()->of($Education)
            ->addIndexColumn()
            ->addColumn('actions', function ($Education) {
                return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $Education['EducationId'] . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>  
                <button class="btn btn-sm btn btn-outline-danger font-13 delete" data-id="' . $Education['EducationId'] . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // ?========================Get Education Details for Edit ========================//

    public function getEducationDetails(Request $request)
    {
        $EducationId = $request->EducationId;
        $EducationDetails = master_education::find($EducationId);
        return response()->json(['EducationDetails' => $EducationDetails]);
    }

    // ?=====================Update Education Details===================
    public function editEducation(Request $request)
    {
        $EducationId = $request->EId;
        $validator = Validator::make($request->all(), [
            'editEducationName' => 'required',
            'editEducationCode' => 'required',
            'editEducationType' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $education = master_education::find($EducationId);
            $education->EducationName = $request->editEducationName;
            $education->EducationCode = $request->editEducationCode;
            $education->EducationType = $request->editEducationType;
            $education->Status = $request->editStatus;
            $query = $education->save();
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Education data has been changed successfully.']);
            }
        }
    }

    public function deleteEducation(Request $request)
    {
        $EducationId = $request->EducationId;
        $query = master_education::find($EducationId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Education data has been Deleted.']);
        }
    }
}
