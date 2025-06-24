<?php
 namespace App\Helpers;
 use Illuminate\Support\Facades\Request;
 use App\Models\CandidateActivity;
 
class CandidateActivityLog{
    public static function addToCandLog($JCId,$Aadhaar,$Description)
    {
    	$log = [];
    	$log['JCId'] = $JCId;
		$log['Aadhaar'] = $Aadhaar;
    	$log['Date'] = now();
    	$log['Description'] = $Description;
    	CandidateActivity::create($log);
    }

    
}
