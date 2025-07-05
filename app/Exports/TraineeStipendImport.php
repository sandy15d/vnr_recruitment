<?php

namespace App\Exports;

use App\Models\jobapply;
use App\Models\jobcandidate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TraineeStipendImport implements toCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $query = DB::table('trainee_stipend')->insert(['TId' => $row['tid'], 'Year' => $row['year'], 'Month' => $row['month'], 'Stipend' => $row['stipend'], 'Expense' => $row['expense'], 'Total' => $row['total'], 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
        }
    }
}
