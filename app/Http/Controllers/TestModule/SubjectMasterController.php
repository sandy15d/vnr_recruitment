<?php

namespace App\Http\Controllers\TestModule;

use App\Http\Controllers\Controller;
use App\Models\TestModule\SubjectMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubjectMasterController extends Controller
{
    public function index()
    {
        $subjects = SubjectMaster::all();
        return view('testmodule.subject_master', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'subject_name' => 'required|unique:subject_master,subject_name',
                'subject_type' => 'required',
            ],
            [
                'subject_name.required' => 'Please enter Subject Name',
                'subject_type.required' => 'Please select Subject Type',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $subject = new SubjectMaster();
            $subject->subject_name = $request->subject_name;
            $subject->subject_type = $request->subject_type;
            $subject->status = $request->status;
            $subject->created_by = auth()->user()->id;
            $subject->save();
            return response()->json(['status' => 200, 'message' => 'Subject Added Successfully.']);
        }
    }

    public function edit($id)
    {
        $subject = SubjectMaster::find($id);
        return response()->json(['status' => 200, 'subject' => $subject]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'edit_subject_name' => 'required|unique:subject_master,subject_name,' . $id,
                'edit_subject_type' => 'required',
            ],
            [
                'subject_name.required' => 'Please enter Subject Name',
                'subject_type.required' => 'Please select Subject Type',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $subject = SubjectMaster::find($id);
            $subject->subject_name = $request->edit_subject_name;
            $subject->subject_type = $request->edit_subject_type;
            $subject->status = $request->edit_subject_status;
            $subject->save();
            return redirect()->back()->with('success', 'Subject Updated Successfully.');
        }
    }


    public function destroy($id)
    {
        $query = SubjectMaster::find($id);
        $query->delete();
        return redirect()->back()->with('success', 'Subject Deleted Successfully.');
    }

    public function get_sub_dept_map(Request $request)
    {
        $subject_id = $request->subject_id;
        $query = DB::select("SELECT d.id,d.department_name,IF(ISNULL(sd.id),'No','YES') AS active FROM core_department d
        LEFT JOIN subject_dept_map sd ON sd.dept_id = d.id AND sd.subject_id = $subject_id
        ORDER BY d.department_name ASC");
        return response()->json(['Sub_Dept_List' => $query]);
    }

    public function map_sub_with_dpt(Request $request)
    {
        $subject_id = $request->subId;
        $department_id = $request->department;
        $sql = 0;
        $check = DB::table('subject_dept_map')->where('subject_id', $subject_id);
        if ($check != null) {
            $check->delete();
        }

        for ($i = 0; $i < count($department_id); $i++) {
            $sql = DB::table('subject_dept_map')->insert(
                ['subject_id' => $subject_id, 'dept_id' => $department_id[$i]]
            );

            $sql = 1;
        }

        if ($sql == 1) {
            return response()->json(['status' => 200, 'message' => 'Department Mapped Successfully.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Something went wrong.']);
        }
    }
}
