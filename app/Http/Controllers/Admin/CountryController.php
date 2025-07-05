<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\master_country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class CountryController extends Controller
{
    public function getAllCountryData()
    {
        $company = master_country::all();
        return datatables()->of($company)
            ->addIndexColumn()
            ->make(true);
    }

}
