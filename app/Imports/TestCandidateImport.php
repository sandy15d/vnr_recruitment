<?php

namespace App\Imports;


use App\Models\jobcandidate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestCandidateImport implements toCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $validator = Validator::make($rows->toArray(), [
            '*.fname' => 'required',
            '*.email' => 'required',
            '*.phone' => 'required',

        ], [
            '*.fname.required' => 'First Name is required in row :attribute',
            '*.email.required' => 'Email is required in row :attribute',
            '*.phone.required' => 'Phone is required in row :attribute',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $errorMessage) {
                preg_match('/row (\d+)/', $errorMessage, $matches);
                $rowNumber = $matches[1] ?? null;
                if ($rowNumber) {
                    $errors[$rowNumber][] = $errorMessage;
                }
            }

            throw new \Exception(json_encode($errors));
        }

        foreach ($rows as $row) {

           $query =  jobcandidate::create(
                [
                    'Title' => $row['title'],
                    'FName' => $row['fname'],
                    'MName' => $row['mname'],
                    'LName' => $row['lname'],
                    'Gender' => $row['gender'],
                    'Email' => $row['email'],
                    'Phone' => $row['phone'],
                    'Nationality' => '1',
                    'Professional' => $row['experience_level'],
                    'for_test'=> 'Y',

                ]
            );

            $JCId = $query->JCId;
            $ReferenceNo = rand(1000, 9999) . date('Y') . $JCId;
            $query1 = jobcandidate::find($JCId);
            $query1->ReferenceNo = $ReferenceNo;
            $query1->save();


        }
    }
}
