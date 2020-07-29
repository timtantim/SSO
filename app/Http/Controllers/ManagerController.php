<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index(Request $request)
    {
        // $get_factory
        $get_all_user=DB::table('users')->where('verify','1')->where('set_auth','0')->paginate(10);
        return view('manager.index')->with([
            'users'=>$get_all_user
        ]);//
    }
}
