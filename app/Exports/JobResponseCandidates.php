<?php

namespace App\Exports;

use App\Models\jobpost;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class JobResponseCandidates implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents
{
    use Exportable;

    public function __construct($JPId)
    {
        $this->JPId = $JPId;
    }

    public function title(): string
    {
        return 'Job & Response';
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Font color (white in this example)
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '509EA0'],// Background color (Cyan in this example)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:AH2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Font color (white in this example)
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '509EA0'],// Background color (Cyan in this example)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->mergeCells('A1:AH1');

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $event->sheet->getDelegate()->freezePane('F3');
            }
        ];
    }

    public function headings(): array
    {
        $JobDetail = jobpost::find($this->JPId);

        return [[
            $JobDetail->JobCode.' , Designation - '.$JobDetail->Title
        ], [
            '#', 'Application Date', 'Source', 'ReferenceNo',
            'Name', 'Date of Birth', 'Gender', 'Marital Status', 'Phone', 'Email ID', 'Address', 'City',
            'District', 'State', 'PinCode', 'Highest Qualification', 'Specialization', 'College/institute', 'Passing Year', 'Percentage/Grade',
            'Work Experience', 'Name of Cr.Company', 'Date of Joining', 'Designation', 'Notice Period', 'salary(Per Month)', 'Annual Package(CTC)',
            'DA@headquarter', 'DA Outside Headquarter', 'Petrol Allowance', 'Phone Allowances', 'Hotel Eligibility', 'Total Work Experience in Year', 'In Month'
        ]];
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        // Initialize the serial number variable
        $this->initializeSerialNumber();

        // Fetch job candidates with related data
        return $this->fetchJobCandidates();
    }

    /**
     * Initialize the serial number variable to 0.
     */
    protected function initializeSerialNumber()
    {
        DB::statement("SET @serial_number := 0");
    }

    /**
     * Fetch job candidates with related data using joins and custom formatting.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function fetchJobCandidates()
    {
        return DB::table('jobcandidates as jc')
            ->select($this->getSelectColumns())
            ->leftJoin('jobapply as ja', 'ja.JCId', '=', 'jc.JCId')
            ->leftJoin('jobpost as jp', 'jp.JPId', '=', 'ja.JPId')
            ->leftJoin('master_education as me', 'me.EducationId', '=', 'jc.Education')
            ->leftJoin('master_specialization as ms', 'ms.SpId', '=', 'jc.Specialization')
            ->leftJoin('master_institute as mi', 'mi.InstituteId', '=', 'jc.College')
            ->leftJoin('master_district as md', 'md.DistrictId', '=', 'jc.District')
            ->leftJoin('core_state as s', 's.id', '=', 'jc.State')
            ->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'ja.ResumeSource')
            ->leftJoin('jf_contact_det', 'jf_contact_det.JCId', '=', 'jc.JCId')
            ->where('jp.JPId', $this->JPId)
            ->get();
    }

    /**
     * Define the columns to select in the query.
     *
     * @return array
     */
    protected function getSelectColumns()
    {
        return [
            DB::raw('@serial_number := @serial_number + 1 AS SerialNumber'),
            DB::raw('DATE_FORMAT(ja.ApplyDate, "%d/%m/%Y") AS Application_Date'),
            'master_resumesource.ResumeSource',
            'jc.ReferenceNo',
            DB::raw('CONCAT_WS(" ", jc.Title, jc.FName, jc.MName, jc.LName) AS Name'),
            'jc.DOB',
            'jc.Gender',
            'jc.MaritalStatus',
            'jc.Phone',
            'jc.Email',
            DB::raw('CONCAT_WS(" ", jc.AddressLine1, jc.AddressLine2, jc.AddressLine3) AS address'),
            'jc.City',
            'md.DistrictName',
            's.state_name',
            'jc.PinCode',
            'me.EducationCode',
            'ms.Specialization',
            'mi.InstituteName',
            'jc.PassingYear',
            'jc.CGPA',
            DB::raw("CASE WHEN jc.Professional = 'P' THEN 'Experienced' ELSE 'Fresher' END AS Work_Experience"),
            'jc.PresentCompany',
            'jc.JobStartDate',
            'jc.Designation',
            'jc.NoticePeriod',
            'jc.GrossSalary',
            'jc.CTC',
            'jc.DAHq',
            'jc.DAOutHq',
            'jc.PetrolAlw',
            'jc.PhoneAlw',
            'jc.HotelElg',
            'jc.TotalYear',
            'jc.TotalMonth',
        ];
    }

}
