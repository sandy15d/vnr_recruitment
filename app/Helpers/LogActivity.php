<?php
 namespace App\Helpers;
 use Illuminate\Support\Facades\Request;
 use App\Models\LogBookActivity;
 
class LogActivity{
    public static function addToLog($subject,$type)
    {
    	$log = [];
    	$log['subject'] = $subject;
		$log['type'] = $type;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    	LogBookActivity::create($log);
    }

    public static function logActivityLists()
    {
    	return LogBookActivity::latest()->get();
    }
}
