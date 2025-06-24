<?php

namespace App\Http\Controllers\TestModule;

use App\Http\Controllers\Controller;
use App\Models\TestModule\QuestionBank;
use App\Models\TestModule\SubjectMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class QuestionBankController extends Controller
{

    public function index()
    {
        $subjects = DB::table('subject_master')->where('status', 'A')->get();
        $QuestionBank = DB::table('question_banks')
            ->select(
                'subject_master.id',
                'subject_master.subject_name',
                'subject_master.status',
                DB::raw("(SELECT COUNT(question_banks.id) FROM question_banks WHERE question_banks.subject_id = subject_master.id) as total_question"),
                DB::raw("GROUP_CONCAT(DISTINCT(core_department.department_code) ) as department")
            )
            ->leftJoin('subject_master', 'question_banks.subject_id', '=', 'subject_master.id')
            ->leftJoin('subject_dept_map', 'question_banks.subject_id', '=', 'subject_dept_map.subject_id')
            ->leftJoin('core_department', 'subject_dept_map.dept_id', '=', 'core_department.id')
            ->groupBy('subject_master.id')
            ->get();

        return view('testmodule.question_bank', compact('subjects', 'QuestionBank'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'subject_id' => 'required',
                'suitable_for' => 'required',
                'level' => 'required',
                'question_type' => 'required',
                'question' => 'required',
            ],
        );

        $validator->sometimes(['option_a', 'option_b', 'option_c', 'option_d', 'answer'], 'required', function ($request) {
            return $request->question_type === 'MCQ';
        });

        $validator->sometimes('true_false_answer', 'required', function ($request) {
            return $request->question_type === 'True/False';
        });

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $QuestionBank = new QuestionBank();
            $QuestionBank->subject_id = $request->subject_id;
            $QuestionBank->suitable_for = $request->suitable_for;
            $QuestionBank->level = $request->level;
            $QuestionBank->question_type = $request->question_type;
            $QuestionBank->question = $request->question;
            $QuestionBank->option_a = $request->option_a ?? null;
            $QuestionBank->option_b = $request->option_b ?? null;
            $QuestionBank->option_c = $request->option_c ?? null;
            $QuestionBank->option_d = $request->option_d ?? null;
            if ($request->question_type === 'MCQ') {
                $QuestionBank->correct_option = $request->answer;
            } elseif ($request->question_type === 'True/False') {
                $QuestionBank->correct_option = $request->true_false_answer;
            } else {
                $QuestionBank->correct_option = null;
            }

            $QuestionBank->status = 'A';
            $QuestionBank->created_by = auth()->user()->id;
            $QuestionBank->save();
            return response()->json(['status' => 200, 'message' => 'Question Added Successfully.']);
        }
    }


    public function show(Request $request, $id)
    {
        $Subject = SubjectMaster::find($id);
        $Department = DB::table('subject_dept_map')->select('DepartmentCode')
            ->join('master_department', 'subject_dept_map.dept_id', '=', 'master_department.DepartmentId')
            ->where('subject_dept_map.subject_id', $id)
            ->get();
        $QuestionType = ['MCQ', 'True/False', 'Descriptive'];
        $Level = ['Easy', 'Moderate', 'Hard'];
        $SuitableFor = ['Fresher', 'Intermediate', 'Experenced', 'All'];
        return view('testmodule.question_bank_show', compact('Department', 'Subject', 'QuestionType', 'Level', 'SuitableFor'));
    }


    public function edit($id)
    {
        $subjects = DB::table('subject_master')->where('status', 'A')->get();
        $question_detail = QuestionBank::find($id);
       return view('testmodule.question_bank_edit', compact('subjects', 'question_detail'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'subject_id' => 'required',
                'suitable_for' => 'required',
                'level' => 'required',
                'question_type' => 'required',
                'question' => 'required',
            ],
        );

        $validator->sometimes(['option_a', 'option_b', 'option_c', 'option_d', 'answer'], 'required', function ($request) {
            return $request->question_type === 'MCQ';
        });

        $validator->sometimes('true_false_answer', 'required', function ($request) {
            return $request->question_type === 'True/False';
        });

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $QuestionBank = QuestionBank::find($id);
            $QuestionBank->subject_id = $request->subject_id;
            $QuestionBank->suitable_for = $request->suitable_for;
            $QuestionBank->level = $request->level;
            $QuestionBank->question_type = $request->question_type;
            $QuestionBank->question = $request->question;
            $QuestionBank->option_a = $request->option_a ?? null;
            $QuestionBank->option_b = $request->option_b ?? null;
            $QuestionBank->option_c = $request->option_c ?? null;
            $QuestionBank->option_d = $request->option_d ?? null;
            if ($request->question_type === 'MCQ') {
                $QuestionBank->correct_option = $request->answer;
            } elseif ($request->question_type === 'True/False') {
                $QuestionBank->correct_option = $request->true_false_answer;
            } else {
                $QuestionBank->correct_option = null;
            }

            $QuestionBank->status = 'A';
            $QuestionBank->created_by = auth()->user()->id;
            $QuestionBank->save();
            return response()->json(['status' => 200, 'message' => 'Question Updated Successfully.']);
        }
    }


    public function destroy($id)
    {
        $QuestionBank = QuestionBank::find($id);
        $QuestionBank->delete();
        return response()->json(['status' => 200, 'message' => 'Question Deleted Successfully.']);
    }

    public function get_question_bank_questions(Request $request, $id)
    {
        $Type = $request->Type;
        $Level = $request->Level;
        $Suitable = $request->Suitable;
        $Question = $request->Question;
        $Status = $request->Status;

        $QuestionBank = QuestionBank::query();

        if ($Type != '') {
            $QuestionBank->where('question_type', $Type);
        }
        if ($Level != '') {
            $QuestionBank->where('level', $Level);
        }
        if ($Suitable != '') {
            $QuestionBank->where('suitable_for', $Suitable);
        }
        if ($Question != '') {
            $QuestionBank->where('question', 'like', '%' . $Question . '%');
        }
        if ($Status != '') {
            $QuestionBank->where('status', $Status);
        }

        $QuestionBank->whereSubjectId($id);

        return DataTables::of($QuestionBank)
            ->addIndexColumn()
            ->editColumn('question', function ($row) {
                return strip_tags($row->question);
            })
            ->editColumn('option_a', function ($row) {
                return strip_tags($row->option_a);
            })
            ->editColumn('option_b', function ($row) {
                return strip_tags($row->option_b);
            })
            ->editColumn('option_c', function ($row) {
                return strip_tags($row->option_c);
            })
            ->editColumn('option_d', function ($row) {
                return strip_tags($row->option_d);
            })
            ->addColumn('True_False', function ($row) {
                if ($row->correct_option == 'True') {
                    return 'True';
                } elseif ($row->correct_option == 'False') {
                    return 'False';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route("question_bank.edit", $row->id) . '" target="_blank"><i class="bx bx-edit text-primary font-22"></i></a>';
                $btn .= ' <a href="javascript:void(0)"><i class="bx bx-trash text-danger ml-2 font-22 delete" data-id="' . $row->id . '"></i></a>';
                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);

    }
}
