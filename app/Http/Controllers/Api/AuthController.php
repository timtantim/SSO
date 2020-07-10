<?php

namespace App\Http\Controllers\Api;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'name'=>'required',
            'password'=>'required|confirmed'
        ]);
        $user=User::firstOrNew(['email'=>$request->email]);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->update_month=date('n');
        $user->password_no_hash=$request->password;
        $user->save();

        $http=new Client;
        //post(Url('oauth/token')
        $response=$http->post(Url('oauth/token'),[
            'verify'=>false,
            'form_params'=>[
                'grant_type'=>'password',
                'client_id'=>'2',
                'client_secret'=>'UzdC11W37rGhtimTplGbeMKABGVNSwYYxOvsTd9Y',
                'username'=>$request->email,
                'password'=>$request->password,
                'scope'=>''
            ]
        ]);
        return response(['data'=>json_decode((string)$response->getBody(),true)]);

    }
    public function login(Request $request)
    {
        Log::info('紀錄Login的請求: ' . $request);
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $user=User::where('email',$request->email)->first();
        Log::info('紀錄Login返回值: ' . $user);
        if(!$user)
        {
            return response([
                'status'=>'error',
                'message'=>'該用戶不存在'
            ],403);
        }
        if(Hash::check($request->password,$user->password))
        {
            $http=new Client;
            //post(Url('oauth/token')
            $response=$http->post(Url('oauth/token'),[
                'verify'=>false,
                'form_params'=>[
                    'grant_type'=>'password',
                    'client_id'=>'2',
                    'client_secret'=>'UzdC11W37rGhtimTplGbeMKABGVNSwYYxOvsTd9Y',
                    'username'=>$request->email,
                    'password'=>$request->password,
                    'scope'=>''
                ]
            ]);

        // $user=User::where('email',$request->email)->first();
        $get_update_month=$user->update_month;
        if((int)$get_update_month<(int)date('n') && (int)$get_update_month !=12){
            return response([
                'status'=>'error',
                'message'=>'請更新密碼'
            ],403);
        }
            return response(['data'=>json_decode((string) $response->getBody(),true),'default_website'=>$user->default_system]);
        }else{
            return response([
                'status'=>'error',
                'message'=>'密碼錯誤'
            ],403);
        }
    }
    public function logout(Request $request){

        $accessToken = $request->user()->token();
        // $accessToken = auth()->user()->token();
        // $accessToken = Auth::user()->token();
        $accessToken->revoke();
        return response()->json(['status' => 'success']);
    }
    public function checkUser(Request $request)
    {
        $get_id=auth()->user()->id;
        return response()->json(['id' => $get_id]);
    }
    public function updatepass(Request $request){
        $user=User::firstOrNew(['email'=>$request->email]);
        $user->password=bcrypt($request->password);
        $user->update_month=date('n');
        $user->password_no_hash=$request->password;
        $user->save();

        $http=new Client;
        //post(Url('oauth/token')
        $response=$http->post(Url('oauth/token'),[
            'verify'=>false,
            'form_params'=>[
                'grant_type'=>'password',
                'client_id'=>'2',
                'client_secret'=>'UzdC11W37rGhtimTplGbeMKABGVNSwYYxOvsTd9Y',
                'username'=>$request->email,
                'password'=>$request->password,
                'scope'=>''
            ]
        ]);
        $user=User::where('email',$request->email)->first();
        return response(['data'=>json_decode((string) $response->getBody(),true),'default_website'=>$user->default_system]);
    }
}
