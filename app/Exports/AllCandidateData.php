<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\LazyCollection;

class AllCandidateData implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    use Exportable;

    public function title(): string
    {
        return 'All Candidates';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Serial Number', 'MRF', 'JCId', 'ReferenceNo', 'Application Date',
            'Name', 'FatherName', 'Email', 'Phone', 'Work Experience',
            'Total Year', 'Total Month', 'Present Company', 'Designation',
            'Gross Salary', 'CTC', 'Education Code', 'Specialization',
            'Institute Name', 'Passing Year', 'CGPA', 'Gender', 'Address',
            'District Name', 'State Name', 'Ref Person', 'Ref Company',
            'Ref Designation', 'Ref Contact', 'Ref Mail', 'Last Status'
        ];
    }

    public function collection()
    {
        set_time_limit(1200);
        // Use LazyCollection to efficiently handle large datasets
        return LazyCollection::make(function () use (&$serialNumber) {
            // Fetch data from the database in chunks to reduce memory usage
            DB::table('jobcandidates as jc')
                ->select([
                  /*  'jp.JobCode as MRF',*/
                    'jc.JCId',
                    'jc.ReferenceNo',
                    DB::raw('DATE_FORMAT(ja.ApplyDate, "%d/%m/%Y") as Application_Date'),
                    DB::raw('CONCAT_WS(" ", jc.FName, jc.MName, jc.LName) as Name'),
                    'jc.FatherName',
                    'jc.Email',
                    'jc.Phone',
                ])
                ->leftJoin('jobapply as ja', 'ja.JCId', '=', 'jc.JCId')


                ->orderBy('jc.JCId')
                ->chunk(1000, function ($results)  {
                    foreach ($results as $item) {
                        yield [

                            'MRF' => $item->MRF,
                            'JCId' => $item->JCId,
                            'ReferenceNo' => $item->ReferenceNo,
                            'Application_Date' => $item->Application_Date,
                            'Name' => $item->Name,
                            'FatherName' => $item->FatherName,
                            'Email' => $item->Email,
                            'Phone' => $item->Phone,
                            /*'Work_Experience' => $item->Work_Experience,
                            'TotalYear' => $item->TotalYear,
                            'TotalMonth' => $item->TotalMonth,
                            'PresentCompany' => $item->PresentCompany,
                            'Designation' => $item->Designation,
                            'GrossSalary' => $item->GrossSalary,
                            'CTC' => $item->CTC,
                            'EducationCode' => $item->EducationCode,
                            'Specialization' => $item->Specialization,
                            'InstituteName' => $item->InstituteName,
                            'PassingYear' => $item->PassingYear,
                            'CGPA' => $item->CGPA,
                            'Gender' => $item->Gender,
                            'Address' => $item->address,
                            'DistrictName' => $item->DistrictName,
                            'StateName' => $item->StateName,
                            'RefPerson' => $item->RefPerson,
                            'RefCompany' => $item->RefCompany,
                            'RefDesignation' => $item->RefDesignation,
                            'RefContact' => $item->RefContact,
                            'RefMail' => $item->RefMail,*/

                        ];
                    }
                });
        });
    }
}

