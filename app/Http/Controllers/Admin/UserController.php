<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin\master_department;
use App\Models\Admin\master_user;
use App\Models\Admin\master_employee;
use App\Models\User;
use App\Models\UserDepartmentMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogActivity;
use App\Mail\NewUserMail;
use App\Models\Admin\master_user_permission;
use DataTables;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function userlist()
    {

        $company_list = DB::table("core_company")->orderBy('id', 'asc')->pluck("company_code", "id");
        return view('admin.userlist', compact('company_list'));
    }


    public function getEmployee(Request $request)
    {


        $Employee = master_employee::select(
            DB::raw("CONCAT(Fname,' ',Lname , ' - ',EmpCode) AS name"),
            'EmployeeID'
        )
            ->where('CompanyId', $request->CompanyId)
            ->where('EmpStatus', 'A')
            ->pluck('name', 'EmployeeID');
        return response()->json($Employee);
    }


    public function getEmployeeDetail(Request $request)
    {
        $EmployeeID = $request->EmployeeID;
        $EmployeeDetail = master_employee::find($EmployeeID);
        return response()->json(['EmployeeDetail' => $EmployeeDetail]);
    }

    // ?===============Insert User records in Database===================
    public function addUser(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'Employee' => 'required|unique:users,id',
            'Username' => 'required',
            'Password' => 'required',
            'UserType' => 'required',
            'Contact' => 'required',
            'Email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            $user = new master_user;
            $user->id = $request->Employee;
            $user->name = getFullName($request->Employee);
            $user->Username = $request->Username;
            $user->email = $request->Email;
            $user->role = $request->UserType;
            $user->Contact = $request->Contact;
            $user->Status = $request->Status;
            $user->password = Hash::make($request->Password);
            $query = $user->save();

            $userDetails = master_user::find($request->Employee);

            $roles = [
                "A" => "Admin",
                "R" => "Recruiter",
                "H" => "Employee"
            ];
            $name = getFullName($request->Employee);
            $role = $userDetails->role;
            if (array_key_exists($role, $roles)) {
                $r = $roles[$role];
            }

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                LogActivity::addToLog('New User' . getFullName($request->Employee) . ' is created', 'Create');
                $details = [
                    "subject" => 'New user account created as ' . $r,
                    "Employee" => $name,
                    "Role" => $r,
                    "Username" => $request->Username,
                    "Password" => $request->Password
                ];
                if (CheckCommControl(1) == 1) {  //if New user created by Admin communication control is on
                    Mail::to($request->Email)->send(new NewUserMail($details));
                }

                return response()->json(['status' => 200, 'msg' => 'New User has been successfully created.']);
            }

        }
    }

    // ?====================Get All User Data From Datatabse=====================

    public function getAllUser()
    {
        // Retrieve all users except user with id=1084
        $users = master_user::where('id', '!=', '1084')->get();
        return datatables()->of($users)
            ->addIndexColumn()  // Add a column for index numbers
            ->addColumn('actions', function ($user) {  // Add a column for user actions
                $buttons = '';

                // Create delete button for the user
                $buttons .= '<button class="btn btn-sm btn-outline-danger font-12" data-id="' . $user->id . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash delete"></i></button>';

                // Create change password button for the user
                $buttons .= '<button class="btn btn-sm btn-outline-primary font-12 cngpwd" data-id="' . $user->id . '"><i class="fadeIn animated bx bx-key"></i></button>';

                // Create permission button for admin and recruiter users
                if ($user->role != 'A') {
                    $buttons .= '<button class="btn btn-sm btn-outline-info font-12 setpermission" data-id="' . $user->id . '"><i class="fadeIn animated bx bx-lock"></i></button>';
                }

                return $buttons;
            })
            ->addColumn('UserType', function ($user) {  // Add a column for user types
                switch ($user->role) {
                    case 'H':
                        return 'Employee';
                        break;
                    case 'R':
                        return 'Recruiter';
                        break;
                    case 'A':
                        return 'Admin';
                        break;
                    default:
                        return '-';
                }
            })
            ->addColumn('Status', function ($user) {  // Add a column for user statuses
                $options = [
                    'A' => 'Active',
                    'D' => 'Deactive',
                ];

                $select = '<select name="Status" id="Status' . $user->id . '" class="form-control form-select form-select-sm d-inline" disabled style="width: 100px;" onchange="changeStatus(' . $user->id . ')"><option value="">Select</option>';

                foreach ($options as $value => $label) {
                    $selected = '';

                    if ($user->Status == $value) {
                        $selected = ' selected';
                    }

                    $select .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
                }

                $select .= '</select> <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" id="statusedit' . $user->id . '" onclick="editstatus(' . $user->id . ')" style="font-size: 16px;cursor: pointer;"></i>';

                return $select;
            })
            ->rawColumns(['actions', 'UserType', 'Status'])  // Set columns that contain HTML
            ->make(true);  // Return the datatable as JSON response
    }


    public function deleteUser(Request $request)
    {
        $UserId = $request->UserId;
        $query = master_user::find($UserId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'User  data has been Deleted.']);
        }
    }

    public function cngUserPwd(Request $request)
    {
        $UId = $request->UId;
        $validator = Validator::make($request->all(), [
            'CnfPassword' => 'required',
            'NewPassword' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $query = master_user::where('id', $UId)->update(['password' => bcrypt($request->NewPassword), 'updated_at' => date('Y-m-d H:i:s')]);

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Password has been changed successfully.']);
            }
        }
    }


    public function getPermission(Request $request)
    {
        $userId = $request->input('UserId');
        $role = User::find($userId)->role;
        $query = DB::table('permission as p')
            ->select('p.*', DB::raw("IF(up.PId IS NULL, 'NO', 'YES') AS active"))
            ->leftJoin('user_permission as up', function ($join) use ($userId) {
                $join->on('up.PId', '=', 'p.PId')
                    ->where('up.UserId', '=', $userId);
            })->where('p.Permission_For', $role)
            ->get();

        return response()->json(['Permission' => $query]);
    }


    public function setpermission(Request $request)
    {
        $userId = $request->UserId;
        $permissions = $request->permission;

        try {
            DB::beginTransaction();

            master_user_permission::where('UserId', $userId)->delete();

            if (!empty($permissions)) {
                foreach ($permissions as $permission) {
                    $userPermission = new master_user_permission;
                    $userPermission->UserId = $userId;
                    $userPermission->PId = $permission;
                    $userPermission->save();
                }
            }

            DB::commit();
            return response()->json(['status' => 200, 'msg' => 'Permission has been set successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function changeUserStatus(Request $request, master_user $user)
    {
        ['id' => $id, 'status' => $status] = $request->only(['id', 'status']);

        $query = $user->where('id', $id)->update([
            'Status' => $status,
            'updated_at' => now()
        ]);

        return response()->json(
            $query
                ? ['status' => 200, 'msg' => 'User Status has been changed successfully.']
                : ['status' => 400, 'msg' => 'Something went wrong..!!']
        );
    }

    public function user_department_list()
    {
        $user_list = User::whereRole('H')->where('Status', 'A')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        $departments = master_department::all();
        $user_department = UserDepartmentMap::pluck(DB::raw("concat(user_id ,'+', department_id) as row"))->toArray();
        return view('admin.user_department_list', compact('user_list', 'departments', 'user_department'));

    }

    public function map_user_department(Request $request)
    {
        $DeptId = $request->DeptId;
        $UserId = $request->UserId;

        $check = DB::table('user_department_map')->where('department_id', $DeptId)->where('user_id', $UserId)->first();

        if ($check !== null) {
            $query = DB::table('user_department_map')
                ->where('department_id', $DeptId)->where('user_id', $UserId)
                ->delete();
        } else {
            $query = new UserDepartmentMap();
            $query->department_id = $DeptId;
            $query->user_id = $UserId;
            $query->save();
        }

        if ($query) {
            return response()->json(array('status' => 200));
        }
    }
}
