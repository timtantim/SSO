<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Notify_Manager;
use Illuminate\Support\Facades\Mail;

class ManagerApiController extends Controller
{
    public function setauthuser(Request $request){
        $get_user_id=$request->user_id;
        
    }
}
