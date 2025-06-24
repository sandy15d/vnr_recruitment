<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\communication_controll;
use Illuminate\Http\Request;


class CommunicationController extends Controller
{
    public function setCommunication(Request $request)
    {
        $query = communication_controll::find($request->id);
        if ($query->is_active == 1) {
            $query->is_active = 0;
        } else {
            $query->is_active = 1;
        }
        $query->save();
        return json_encode(array('status' => 200));
    }
}
