<?php
 namespace App\Helpers;
 use App\Models\Notification;
 
class UserNotification{
    public static function notifyUser($userid,$title,$description)
    {
    	$notify = [];
    	$notify['userid'] = $userid;
		$notify['title'] = $title;
    	$notify['description'] = $description;
    	Notification::create($notify);
    }
}
