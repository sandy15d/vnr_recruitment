<?php

namespace App\Http\Controllers\Common;

use App\Helpers\CandidateActivityLog;
use App\Mail\InterviewerPanelMail;
use App\Mail\InterviewMailOnline;
use App\Mail\SecondRoundInterviewMail;
use App\Models\Admin\master_employee;
use App\Models\jobpost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\InterviewMail;
use App\Mail\RegretMail;
use App\Models\jobapply;
use App\Models\jobcandidate;
use App\Models\OfferLetter;
use App\Models\screen2ndround;
use App\Models\screening;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class TrackerController extends Controller
{
    public function TechnicalScreening()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $JobQuery = jobpost::query();
        if (Auth::user()->role == 'R') {
            $JobQuery->where('CreatedBy', Auth::user()->id);
        }

        $emplist = master_employee::where('EmpStatus', 'A')->orderBy('Fname', 'asc')->groupBy('EmpCode')->get();



        return view('common.technical_screening', compact('company_list', 'emplist'));
    }

    public function getTechnicalSceeningCandidate(Request $request)
    {

        $usersQuery = screening::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Name = $request->Name;
        $JPId = $request->JPId;
        $Status = $request->Status;
        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("screening.ScrCmp", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.ScrDpt", $Department);
        }

        if ($Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%$Name%")->orWhere("jobcandidates.LName", 'like', "%$Name%")->orWhere("jobcandidates.Phone", 'like', "%$Name%");
        }
        if ($JPId != '') {
            $usersQuery->where("jobpost.JPId", $JPId);
        }

        if ($Status == 'All') {
            // No filter condition for All status
        } else if ($Status != '') {
            if ($Status == 'Pending') {
                $usersQuery->where('screening.ScreenStatus', null);
            } else {
                $usersQuery->where('screening.ScreenStatus', $Status);
            }
        } else {
            // Default show pending
            $usersQuery->where('screening.ScreenStatus', null);
        }


        $data = $usersQuery->select('screening.*', 'jobapply.FwdTechScr', 'jobcandidates.JCId', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.Phone', 'jobcandidates.Email', 'jobcandidates.ReferenceNo', 'jobpost.JobCode', 'jobcandidates.BlackList')
            ->join('jobapply', 'jobapply.JAId', '=', 'screening.JAId')
            ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where('jobapply.FwdTechScr', 'Yes')
            ->where('jobpost.Status', 'Open')
            ->where('jobpost.JobPostType', 'Regular')
            ->orderBy('ScId', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function ($data) {
                return '<input type="checkbox" class="japchks" data-id="' . $data->JAId . '" name="selectCand" id="selectCand" value="' . $data->JAId . '">';
            })
            ->addColumn('Name', function ($data) {
                $sendingId = base64_encode($data->JAId);
                return '<a href="' . route("candidate_detail", "jaid=$sendingId") . '" target="_blank" class="text-success">' . $data->FName . ' ' . $data->MName . ' ' . $data->LName . '</a>';
            })
            ->editColumn('Department', function ($data) {
                return getDepartmentCode($data->ScrDpt);
            })
            ->editColumn('sub_department', function ($data) {
                return getSubDepartment($data->SubDepartment);
            })
            /*->editColumn('ScreenedBy', function ($data) {
                return getFullName($data->ScreeningBy);
            })*/
            ->editColumn('ScreenedBy', function ($data) {
                $scrBy = explode(',', $data->ScreeningBy);

                foreach ($scrBy as $row) {
                    $scrBy1[] = getFullName($row);
                }
                return implode(', ', $scrBy1);
            })
            ->editColumn('PanelMail', function ($data) {
                return $data->PanelMail;
            })
            ->addColumn('Action', function ($data) {
                if ($data->BlackList == 1) {
                    return '';
                } else {
                    return '<i class="fa fa-pencil text-dark" style="cursor:pointer" onclick="return EditTracker(' . $data->JAId . ');"></i>';
                }
            })
            ->editColumn('travelElg', function ($data) {
                return $data->travelEligibility;
            })
            ->editColumn('interviewTime', function ($data) {
                return date('h:i a', strtotime($data->IntervTime));
            })
            ->editColumn('IntervPanel', function ($data) {
                $panel = explode(',', $data->IntervPanel);

                foreach ($panel as $row) {
                    $panel1[] = getFullName($row);
                }
                return implode(', ', $panel1);
            })
            ->rawColumns(['chk', 'Name', 'InterviewMail', 'Action'])
            ->make(true);
    }


    public function getScreenDetail(Request $request)
    {
        $JAId = $request->JAId;
        $sql = DB::table('screening')->select('screening.*', 'master_employee.Fname', 'master_employee.Sname', 'master_employee.Lname')->join('master_employee', 'screening.ScreeningBy', '=', 'master_employee.EmployeeID')->where('JAId', $JAId)->first();
        $sql->IntervPanel = array_map('intval', explode(',', $sql->IntervPanel));
        return response()->json(['CandidateDetail' => $sql]);
    }

    public function getInterviewDetail(Request $request)
    {
        $JAId = $request->JAId;
        $sql = DB::table('screening')->select('screen2ndround.*', 'screening.IntervStatus')
            ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
            ->where('screening.JAId', $JAId)->first();
        if ($sql->IntervPanel2 != null) {
            $sql->IntervPanel2 = array_map('intval', explode(',', $sql->IntervPanel2));
        }

        return response()->json(['CandidateDetail' => $sql]);
    }

    public function CandidateTechnicalScreening(Request $request)
    {
        $JAId = $request->JAId;
        $sendingId = base64_encode($JAId);
        $TechScreenStatus = $request->TechScreenStatus;
        $InterviewSchedule = $request->InterviewSchedule;
        $RejectRemark = $request->RejectRemark;
        $InterviewDate = $request->InterviewDate;
        $InterviewTime = $request->InterviewTime;
        $InterviewLocation = ($request->InterviewSchedule == 'offline') ? $request->InterviewLocation : null;
        $InterviewPannel = isset($request->InterviewPannel) ? implode(', ', $request->InterviewPannel) : null;

        $TravelElg = $request->TravelElg;
        $InterviewMail = $request->InterviewMail;
        $InterviewMailToPanel = $request->InterviewMailToPanel;
        $BlackList = $request->BlackList;
        $BlackListRemark = $request->BlackListRemark;
        $TechScreeningDate = $request->TechScreeningDate;  
        $InterviewLink = $request->InterviewLink ?? null;
        //  $curDate = date('Y-m-d');
        $sql = DB::table('screening')
            ->where('JAId', $JAId)
            ->update(['ResScreened' => $TechScreeningDate, 'ScreenStatus' => $TechScreenStatus, 'InterviewMode' => $InterviewSchedule, 'RejectionRem' => $RejectRemark, 'IntervDt' => $InterviewDate, 'IntervTime' => $InterviewTime, 'IntervLoc' => $InterviewLocation, 'IntervPanel' => $InterviewPannel, 'travelEligibility' => $TravelElg, 'SendInterMail' => $InterviewMail, 'PanelMail' => $InterviewMailToPanel, 'IntervLink' => $InterviewLink, 'UpdatedBy' => Auth::user()->id, 'LastUpdated' => now()]);

        $jobapply = jobapply::find($JAId);
        $JCId = $jobapply->JCId;
        $firobid = base64_encode($JCId);
        $JPId = $jobapply->JPId;

        $jobpost = jobpost::find($JPId);
        $Title = $jobpost->Title;

        $jobcandidates = jobcandidate::find($JCId);
        $Aadhaar = $jobcandidates->Aadhaar;
        $CandidateEmail = $jobcandidates->Email;


        $Company = $jobapply->Company;
        $company_name = getcompany_name($Company);
        $Department = $jobapply->Department;
        $DepartmentName = getDepartment($Department);
        if ($BlackList == 1) {
            $query1 = jobcandidate::find($JCId);
            $query1->BlackList = $BlackList;
            $query1->BlackListRemark = $BlackListRemark;
            $query1->save();
            CandidateActivityLog::addToCandLog($JCId, $query1->Aadhaar, 'Candidate is BlackListed because ' . $BlackListRemark);
        }
        if ($TechScreenStatus == 'Shortlist') {
            $details = [
                "subject" => 'Interview Invitation for ' . $DepartmentName . ' on ' . date('d-M-Y', strtotime($InterviewDate)),
                "name" => $jobcandidates->Title.' '.$jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                "reference_no" => $jobcandidates->ReferenceNo,
                "title" => $jobpost->Title,
                "department" => $DepartmentName,
                "interview_date" => $InterviewDate,
                "interview_time" => $InterviewTime,
                "interview_venue" => $InterviewLocation,
                "contact_person" => getFullName(Auth::user()->id),
                'interview_form' => route("candidate-interview-form", "jaid=$sendingId"),
                'firob' => route("firo_b", "jcid=$firobid"),
                'travelEligibility' => $TravelElg,
                'interview_link' => $InterviewLink

            ];
            if ($InterviewMail == 'Yes') {

                if ($InterviewSchedule === 'offline') {  
                    Mail::to($CandidateEmail)->send(new InterviewMail($details));
                } else {
                    Mail::to($CandidateEmail)->send(new InterviewMailOnline($details));
                }
            }

            if ($InterviewMailToPanel == 'Yes') {
                $details = [
                    "subject" => 'Interview schedule for ' . $DepartmentName . ' on ' . date('d-M-Y', strtotime($InterviewDate)),
                    'candidate_name'=>$jobcandidates->Title.' '.$jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                    "interview_date" => $InterviewDate,
                    "interview_time" => $InterviewTime,
                    "interview_venue" => $InterviewLocation,
                    "contact_person" => getFullName(Auth::user()->id),
                    'interview_link' => $InterviewLink,
                    'interview_mode' => $InterviewSchedule
                ];

                foreach ($request->InterviewPannel as $row) {
                    $interviewer = getEmployeeEmailId($row);
                    $interviewerName = getFullName($row);
                    $details['interviewer_name'] = $interviewerName;
                  Mail::to($interviewer)->send(new InterviewerPanelMail($details));
                }
            }
        //================================================ Event Calender =====================================================
            if ($TechScreenStatus == 'Shortlist') {

            // DELETE OLD EVENTS
            $baseDescription = 'Interview Schedule for ' . $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName;

            DB::table('event_calendar')
                ->where('description', 'like', $baseDescription . '%')
                ->where('event_type', 'system')
                ->delete();

            // Prepare event info
            $Event_Title = 'Interview';
            $Event_Description = $baseDescription . ' in ' . $DepartmentName . ' Department';
            $Event_Start = $InterviewDate . ' ' . $InterviewTime;
            $Event_End = $InterviewDate . ' ' . $InterviewTime;

            // ADD recruiter event
            setEventInCalendar($Event_Title, $Event_Description, $Event_Start, $Event_End, Auth::user()->id, 'R');

            // ADD panel events
            foreach ($request->InterviewPannel as $row) {
                setEventInCalendar($Event_Title, $Event_Description, $Event_Start, $Event_End, $row, 'H');
            }
        }
       
        }

        if (isset($request->RegretMail) && $request->RegretMail == 'Yes') {
            $details = [
                "subject" => 'Update on your application status with VNR',
                "name" => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
            ];
            Mail::to($CandidateEmail)->send(new RegretMail($details));
        }

        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Technical Screening Status- ' . $TechScreenStatus);
            return response()->json(['status' => 200, 'msg' => 'Technical Screening Data has been changed successfully.']);
        }
    }


    public function interview_tracker()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $emplist = master_employee::where('EmpStatus', 'A')->orderBy('Fname', 'asc')->get();
        return view('common.interview_tracker', compact('company_list', 'months', 'emplist'));
    }

    public function getInterviewTrackerCandidate(Request $request)
    {
        $usersQuery = screening::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Name = $request->Name;
        $JPId = $request->JPId;


        if (Auth::user()->role == 'R') {
            $usersQuery->where('jp.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("screening.ScrCmp", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.ScrDpt", $Department);
        }
        if ($Name != '') {
            $usersQuery->where("jc.FName", 'like', "%$Name%")->orWhere("jc.LName", 'like', "%$Name%")->orWhere("jc.Phone", 'like', "%$Name%");
        }
        if ($JPId != '') {
            $usersQuery->where("jp.JPId", $JPId);
        }

        $data = $usersQuery->select('screening.*', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jc.Phone', 'jc.Email', 'jc.FIROB_Test', 'jc.InterviewSubmit', 'jp.JobCode', 'sc.IntervDt2', 'sc.IntervLoc2', 'sc.IntervPanel2', 'sc.IntervStatus2', 'intervcost.Travel', 'intervcost.Lodging', 'intervcost.Relocation', 'intervcost.Other')
            ->Join('jobapply as ja', 'ja.JAId', '=', 'screening.JAId')
            ->Join('jobcandidates as jc', 'ja.JCId', '=', 'jc.JCId')
            ->Join('jobpost as jp', 'ja.JPId', '=', 'jp.JPId')
            ->join('manpowerrequisition as mp', 'mp.MRFId', '=', 'jp.MRFId')
            ->join('screen2ndround as sc', 'screening.ScId', '=', 'sc.ScId', 'left')
            ->leftjoin('intervcost', 'intervcost.JAId', '=', 'ja.JAId')
            ->where('mp.CountryId', session('Set_Country'))
            ->where('jp.JobPostType', 'Regular')
            ->where('jp.Status', 'Open')
            ->where('screening.ScreenStatus', 'Shortlist')
            ->orderBy('ScId', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->editColumn('Department', function ($data) {
                return getDepartmentCode($data->ScrDpt);
            })
            ->editColumn('sub_department', function ($data) {
                return getSubDepartment($data->SubDepartment);
            })
            ->addColumn('Name', function ($data) {
                $sendingId = base64_encode($data->JAId);
                return '<a href="' . route("candidate_detail", "jaid=$sendingId") . '" target="_blank" class="text-success">' . $data->FName . ' ' . $data->MName . ' ' . $data->LName . '</a>';
            })
            ->editColumn('IntervEdit', function ($data) {
                return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="editInt' . $data->JAId . '" onclick="editInt(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
            })
            ->editColumn('IntervDt2', function ($data) {
                if ($data->IntervDt2 != null) {
                    return $data->IntervDt2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervLoc2', function ($data) {
                if ($data->IntervLoc2 != null) {
                    return $data->IntervLoc2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervPanel2', function ($data) {
                if ($data->IntervPanel2 != null) {
                    return $data->IntervPanel2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervStatus2', function ($data) {
                if ($data->IntervStatus2 != null) {
                    return $data->IntervStatus2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervEdit2', function ($data) {
                if ($data->IntervStatus == '2nd Round Interview') {
                    return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="editInt_2nd' . $data->JAId . '" onclick="editInt_2nd(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('CompanyEdit', function ($data) {
                if ($data->IntervStatus == 'Selected' || $data->IntervStatus2 == 'Selected') {
                    return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="companyedit' . $data->JAId . '" onclick="editCompany(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('SelectedForC', function ($data) {
                if ($data->SelectedForC != null) {
                    return getcompany_code($data->SelectedForC);
                } else {
                    return '';
                }
            })
            ->editColumn('SelectedForD', function ($data) {
                if ($data->SelectedForD != null) {
                    return getDepartmentCode($data->SelectedForD);
                } else {
                    return '';
                }
            })
            ->addColumn('TestScore', function ($data) {
                $x = '<input type="text" name="TestScore' . $data->JAId . '" id="TestScore' . $data->JAId . '" value="' . $data->TestScore . '" class="frminp" style="width:80px;" disabled> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="TestScoreEdit' . $data->JAId . '" onclick="editTestScore(' . $data->JAId . ')" style="font-size: 16px;cursor: pointer;"></i><button class="btn btn-sm frmbtn btn-success d-none" id="SaveScore' . $data->JAId . '" onclick="return SaveTestScore(' . $data->JAId . ')">Save</button>';
                return $x;
            })
            ->editColumn('FIROB_Test', function ($data) {
                if ($data->FIROB_Test == '1') {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })->editColumn('InterviewSubmit', function ($data) {
                if ($data->InterviewSubmit == '1') {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })

            ->editColumn('IntervPanel', function ($data) {
                $panel = explode(',', $data->IntervPanel);

                foreach ($panel as $row) {
                    $panel1[] = getFullName($row);
                }
                return implode(', ', $panel1);
            })
            ->rawColumns(['chk', 'Name', 'IntervEdit', 'IntervEdit2', 'CompanyEdit', 'TestScore'])
            ->make(true);
    }

    public function first_round_interview(Request $request)
    {
        //dd($request->all());
        $sql = screening::find($request->ScId);
        $sql->InterAtt = 'Yes';
        $sql->IntervStatus = $request->IntervStatus;
        $sql->save();
        $JAId = $sql->JAId;


        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobcandidates.JCId', 'jobcandidates.Aadhaar')->where('JAId', $JAId)->first();
        $jobapply = jobapply::find($JAId);
        $Department = $jobapply->Department;
        $DepartmentName = getDepartment($Department);
        $JCId = $jobapply->JCId;
        $jobcandidates = jobcandidate::find($JCId);
        $CandidateEmail = $jobcandidates->Email;
        if (isset($request->RegretMail) && $request->RegretMail == 'Yes') {

            $details = [
                "subject" => 'Update on your application status with VNR',
                "name" => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
            ];
            Mail::to($CandidateEmail)->send(new RegretMail($details));
        }
        if ($request->IntervStatus == '2nd Round Interview') {
            $SndRound = screen2ndround::updateOrCreate(
                ['ScId' => $request->ScId],
                [
                    'InterviewMode2' => $request->InterviewSchedule,
                    'IntervDt2' => $request->InterviewDate,
                    'IntervTime2' => $request->InterviewTime,
                    'IntervLoc2' => $request->InterviewLocation,
                    'IntervLink2' => $request->InterviewLink,
                    'IntervPanel2' => implode(', ', $request->InterviewPannel),
                    'travelEligibility2' => $request->TravelElg,
                    'SendInterMail2' => $request->InterviewMail,
                    'PanelMail2' => $request->InterviewMailToPanel,
                    'CreatedTime' => now(),
                    'CreatedBy' => Auth::user()->id

                ]
            );

            if ($request->InterviewMail == 'Yes') {
                $details1 = [
                    "subject" => 'Interview Invitation for 2nd round interview for ' . $DepartmentName . ' on ' . date('d-m-Y', strtotime($request->InterviewDate)),
                    "name" => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                    "department" => $DepartmentName,
                    "interview_date" => date('d-m-Y', strtotime($request->InterviewDate)),
                    "interview_time" => $request->InterviewTime,
                    "interview_venue" => $request->InterviewLocation,
                    "contact_person" => getFullName(Auth::user()->id),
                    'travelEligibility' => $request->TravelElg,
                    'interview_link' => $request->InterviewLink,
                    'interview_mode' => $request->InterviewSchedule
                ];
                Mail::to($CandidateEmail)->send(new SecondRoundInterviewMail($details1));
            }

            if ($request->InterviewMailToPanel == 'Yes') {
                $details2 = [
                    "subject" => 'Interview schedule for ' . $DepartmentName . ' on ' . date('d-m-Y', strtotime($request->InterviewDate)),
                    'candidate_name' => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                    "interview_date" => date('d-m-Y', strtotime($request->InterviewDate)),
                    "interview_time" => $request->InterviewTime,
                    "interview_venue" => $request->InterviewLocation,
                    "contact_person" => getFullName(Auth::user()->id),
                    'interview_link' => $request->InterviewLink,
                    'interview_mode' => $request->InterviewSchedule
                ];
                foreach ($request->InterviewPannel as $row) {

                    $interviewer = getEmployeeEmailId($row);
                    $interviewerName = getFullName($row);
                    $details2['interviewer_name'] = $interviewerName;

                    Mail::to($interviewer)->send(new InterviewerPanelMail($details2));
                }
            }

            DB::table('event_calendar')->insert([
                'title' => 'Interview',
                'description' => 'Interview Schedule for ' . $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName . ' in ' . $DepartmentName . ' Department',
                'start_time' => $request->InterviewDate . ' ' . $request->InterviewTime,
                'end_time' => $request->InterviewDate . ' ' . $request->InterviewTime,
                'belong_to' => Auth::user()->id,
                'type' => 'R'
            ]);

            foreach ($request->InterviewPannel as $row) {
                DB::table('event_calendar')->insert([
                    'title' => 'Interview',
                    'description' => 'Interview Schedule for ' . $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName . ' in ' . $DepartmentName . ' Department',
                    'start_time' => $request->InterviewDate . ' ' . $request->InterviewTime,
                    'end_time' => $request->InterviewDate . ' ' . $request->InterviewTime,
                    'belong_to' => $row,
                    'type' => 'H'
                ]);
            }
        }
        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($query->JCId, $query->Aadhaar, 'Candidate Interview Status - ' . $request->IntervStatus);
            return response()->json(['status' => 200, 'msg' => '1st Interview Data has been changed successfully.']);
        }
    }

    public function second_round_interview(Request $request)
    {

        $SCId = $request->ScId_2nd;
        $sql = DB::table('screen2ndround')
            ->where('ScId', $SCId)
            ->update(['IntervStatus2' => $request->IntervStatus2, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);


        $sql = screening::find($SCId);
        $JAId = $sql->JAId;
        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobcandidates.JCId', 'jobcandidates.Aadhaar')->where('JAId', $JAId)->first();
        CandidateActivityLog::addToCandLog($query->JCId, $query->Aadhaar, 'Candidate 2nd Round Interview Status - ' . $request->IntervStatus2);
        if (isset($request->RegretMail2) && $request->RegretMail2 == 'Yes') {
            $jobapply = jobapply::find($JAId);
            $JCId = $jobapply->JCId;
            $jobcandidates = jobcandidate::find($JCId);
            $CandidateEmail = $jobcandidates->Email;
            $details = [
                "subject" => 'Update on your application status with VNR',
                "name" => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
            ];
            Mail::to($CandidateEmail)->send(new RegretMail($details));
        }

        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => '2nd Interview Data has been changed successfully.']);
        }
    }

    public function select_cmp_dpt_for_candidate(Request $request)
    {
        $sql = screening::find($request->ScId_cmp);
        $sql->SelectedForC = $request->SelectedForC;
        $sql->SelectedForD = $request->SelectedForD;
        $sql->save();

        $JAId = $sql->JAId;

        $query = new OfferLetter;
        $query->JAId = $JAId;
        $query->Company = $request->SelectedForC;
        $query->Department = $request->SelectedForD;
        $query->CreatedTime = now();
        $query->Year = date('Y');
        $query->CreatedBy = Auth::user()->id;
        $query->save();

        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobcandidates.JCId', 'jobcandidates.Aadhaar')->where('JAId', $JAId)->first();

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($query->JCId, $query->Aadhaar, 'Candidate Slected For - ' . getDepartmentCode($request->SelectedForD) . ' - ' . getcompany_code($request->SelectedForC));
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully.']);
        }
    }

    public function update_interview_cost(Request $request)
    {
        $JAId = $request->IntervCost_JAId;
        $Travel = $request->Travel;
        $Lodging = $request->Lodging;
        $Relocation = $request->Relocation;
        $Other = $request->Other;

        $total = $Travel + $Lodging + $Relocation + $Other;
        $chk = DB::table('intervcost')->where('JAId', $JAId)->first();
        if ($chk) {
            $query = DB::table('intervcost')
                ->where('JAId', $JAId)
                ->update(['Travel' => $Travel, 'Lodging' => $Lodging, 'Relocation' => $Relocation, 'Other' => $Other, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
        } else {
            $query = DB::table('intervcost')
                ->insert(['Travel' => $Travel, 'Lodging' => $Lodging, 'Relocation' => $Relocation, 'Other' => $Other, 'JAId' => $JAId, 'CreatedTime' => now(), 'CreatedBy' => Auth::user()->id]);
        }

        $sql = jobapply::find($JAId);
        $JCId = $sql->JCId;
        $sql2 = jobcandidate::find($JCId);
        $Aadhaar = $sql2->Aadhaar;
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Interview Cost - ' . $total);
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully.']);
        }
    }

    public function get_interview_cost(Request $request)
    {
        $JAId = $request->JAId;
        $query = DB::table('intervcost')->where('JAId', $JAId)->first();
        if ($query) {
            return response()->json(['status' => 200, 'data' => $query]);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
}
