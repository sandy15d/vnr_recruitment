<?php

namespace App\Exports;

use Citco\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class HodMrfWiseData implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    public function __construct($mrfid)
    {
        $this->mrfid = $mrfid;
    }

    public function title(): string
    {
        return "MRF Wise Data";
    }

    public function headings(): array
    {
        return ['S.No', 'Date', 'CV Received', 'CV Screening', 'HR Screening', 'Technical Screening', 'Interview', '2nd Round Interview', 'Job Offered', 'Offer Accepted', 'Joined'];
    }


    public function collection()
    {
        $MRFId = $this->mrfid;
        $startDate = Carbon::parse(DB::table('manpowerrequisition')->where('MRFId', $MRFId)->value('CreatedTime'));

        // Get the current date
        $endDate = Carbon::now();

        while($startDate <= $endDate) {
            $date = $startDate->format('Y-m-d');
            $startDate->addDay();
            $data[] = $date;

        }
    }
}
