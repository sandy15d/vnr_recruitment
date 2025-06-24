<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin\master_state;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class StateController extends Controller
{
    // ?=====================Load State Page===================
    function state()
    {
        $country_list = DB::table("core_country")->pluck("country_name", "id");
        return view('admin.state', compact('country_list'));
    }


    // ?===============Insert Company records in Database===================
    public function addState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'StateName' => 'required',
            'StateCode' => 'required',
            'Country' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            // DB::enableQueryLog();
            $State = new master_state;
            $State->StateName = $request->StateName;
            $State->StateCode = $request->StateCode;
            $State->Country = $request->Country;
            $State->Status = $request->Status;
            $State->CreatedBy = Auth::user()->id;
            $query = $State->save();
            //$sql = DB::getQueryLog();
            //dd($sql);
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'New State has been successfully created.']);
            }
        }
    }

    // ?====================Get All Company Data From Datatabse=====================

    public function getAllStateData()
    {
        $state = DB::table('master_state')->join('core_country', 'master_state.Country', '=', 'core_country.id')->where('core_country.id', '=', session('Set_Country'))
            ->select(['master_state.StateId', 'master_state.StateName', 'master_state.StateCode', 'core_country.country_name', 'master_state.Status']);

        return datatables()->of($state)
            ->addIndexColumn()
            ->addColumn('actions', function ($state) {
                return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $state->StateId . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>
                <button class="btn btn-sm btn btn-outline-danger font-13 delete" data-id="' . $state->StateId . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // ?========================Get State Details for Edit ========================//

    public function getStateDetails(Request $request)
    {
        $StateId = $request->StateId;
        $StateDetails = master_state::find($StateId);
        return response()->json(['StateDetails' => $StateDetails]);
    }

    // ?=====================Update State Details===================
    public function editState(Request $request)
    {
        $StateId = $request->stid;
        $validator = Validator::make($request->all(), [
            'editStateName' => 'required',
            'editStateCode' => 'required',
            'editCountry' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {
            // DB::enableQueryLog();
            $State = master_state::find($StateId);
            $State->StateName = $request->editStateName;
            $State->StateCode = $request->editStateCode;
            $State->Country = $request->editCountry;
            $State->Status = $request->editStatus;
            $State->UpdatedBy = Auth::user()->id;
            $State->LastUpdated = now();
            $query = $State->save();
            // $sql = DB::getQueryLog();
            //  dd($sql);

            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'State data has been changed successfully.']);
            }
        }
    }

    // !=======================Delete Company ===============================//

    public function deleteState(Request $request)
    {
        $StateId = $request->StateId;
        $query = master_state::find($StateId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'State data has been Deleted.']);
        }
    }

    // *====================== Synchronize Company Data From ESS =============================//



    //!==========================================================================================//
    function gen_states()
    {
        $country_list = DB::table("core_country")->pluck("country_name", "id");
        return view('admin.gen_states', compact('country_list'));
    }

    public function getAllStateData_General()
    {
        $state = DB::table('core_state')->join('core_country', 'core_state.country_id', '=', 'core_country.id')
            ->where('core_state.country_id', '=', session('Set_Country'))
            ->select(['core_state.id', 'core_state.state_name', 'core_state.state_code', 'core_country.country_name', 'core_state.is_active']);

        return datatables()->of($state)
            ->addIndexColumn()
            ->addColumn('actions', function ($state) {
                return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $state->id . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>
                <button class="btn btn-sm btn btn-outline-danger font-13 delete" data-id="' . $state->id . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function addState_general(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'StateName' => 'required',
            'StateCode' => 'required',
            'Country' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $query = DB::table('states')
                ->insert([
                    'StateName' => $request->StateName,
                    'StateCode' => $request->StateCode,
                    'CountryId' => $request->Country,
                    'Status' => $request->Status,

                ]);
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'New State has been successfully created.']);
            }
        }
    }

    public function getStateDetails_general(Request $request)
    {
        $StateId = $request->StateId;
        $StateDetails = DB::table('states')->where('StateId', $StateId)->first();
        return response()->json(['StateDetails' => $StateDetails]);
    }

    public function editState_general(Request $request)
    {
        $StateId = $request->stid;
        $validator = Validator::make($request->all(), [
            'editStateName' => 'required',
            'editStateCode' => 'required',
            'editCountry' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $query = DB::table('states')
                ->where('StateId', $StateId)
                ->update([
                    'StateName' => $request->editStateName,
                    'StateCode' => $request->editStateCode,
                    'CountryId' => $request->editCountry,
                    'Status' => $request->editStatus,
                ]);
            if (!$query) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'State data has been changed successfully.']);
            }
        }
    }

    public function deleteState_general(Request $request)
    {
        $StateId = $request->StateId;

        $query = DB::table('states')->where('StateId', $StateId)->delete();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'State data has been Deleted.']);
        }
    }


    public function sync()
    {
        try {
            // Retrieve the API key and base URL
            $apiData = DB::table('core_api_setup')->first();
            $apiKey = $apiData->api_key;
            $baseUrl = $apiData->base_url;

            // Make the GET request with the correct headers
            $response = Http::withHeaders([
                'api-key' => $apiKey, // Setting the 'api-key' header as required
                'Accept' => 'application/json',
            ])->get("$baseUrl/api/states");

            // Check if the response is successful
            if ($response->failed()) {
                // Handle unsuccessful responses
                Log::error('API sync failed', ['status' => $response->status(), 'response' => $response->body()]);
                return response()->json(['status' => 400, 'msg' => 'Failed to synchronize APIs.']);
            }

            // Parse the JSON response
            $data = $response->json();

            // Validate the structure of the response
            if (!isset($data['list']) || !is_array($data['list'])) {
                Log::error('Invalid API response structure', ['response' => $data]);
                return response()->json(['status' => 400, 'msg' => 'Unexpected API response format.']);
            }

            // Prepare data for batch insertion
            $apiRecords = array_map(function ($value) {
                return [
                    'StateId' => $value['id'] ?? null,
                    'CountryId' => $value['country_id'] ?? '',
                    'StateName' => $value['state_name'] ?? '',
                    'StateCode' => $value['short_code'] ?? '',
                    'NumericCode' => $value['state_code'] ?? '',
                    'EffectiveDate' => $value['effective_date'] ?? '',
                    'Status' => ($value['is_active'] == 1) ? 'A' : 'D',
                ];
            }, $data['list']);

            // Use a transaction to ensure atomic operation

            DB::table('states')->truncate();
            DB::table('states')->insert($apiRecords); // Batch insert for performance


            return response()->json(['status' => 200, 'msg' => 'API synchronized successfully.']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific exceptions
            Log::error('Database error during API sync', ['error' => $e->getMessage()]);
            return response()->json(['status' => 500, 'msg' => 'Database error occurred.']);
        }
    }
}
