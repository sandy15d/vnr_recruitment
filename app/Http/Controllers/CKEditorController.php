<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            
            $fileName = pathinfo($originName, PATHINFO_FILENAME);

            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $request->file('upload')->storeAs('Recruitment/questionbank', $fileName, 's3');
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            
            // Get S3 URL for the uploaded file
            $url = Storage::disk('s3')->url('Recruitment/questionbank/'.$fileName);
            
            $msg = 'Image successfully uploaded'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }
}