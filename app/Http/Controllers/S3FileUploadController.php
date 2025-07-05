<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class S3FileUploadController extends Controller
{
public function index()
{
    try {
        $files = Storage::disk('s3')->files('Recruitment/Documents');
        $fileUrls = array_map(function ($file) {
            return Storage::disk('s3')->url($file);
        }, $files);
    } catch (\Exception $e) {
        \Log::error("S3 error: " . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to load files from S3.');
    }

    return view('s3-upload', ['files' => $fileUrls]);
}

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('Recruitment/Documents', $fileName, 's3');

            // Set file permissions to public
            Storage::disk('s3')->setVisibility($path, 'public');

            return redirect()->back()->with('success', 'File uploaded successfully!');
        }

        return redirect()->back()->with('error', 'File upload failed!');
    }

    
}