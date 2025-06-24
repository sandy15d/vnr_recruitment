<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\master_company;

class CompanyController extends Controller
{
    public function getAllCompanyData()
    {
        $company = master_company::all();
        return datatables()->of($company)
            ->addIndexColumn()
            ->make(true);
    }
}
