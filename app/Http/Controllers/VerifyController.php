<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;

class VerifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index(Request $request)
    {
        // $get_factory
        $get_all_user=DB::table('users')->paginate(10);
        return view('verify.index')->with([
            'users'=>$get_all_user
        ]);//
    }
}
