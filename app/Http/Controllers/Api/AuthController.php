<?php

namespace App\Http\Controllers\Api;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

use function GuzzleHttp\json_decode;

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
        $user->default_system=$request->default_system;
        $user->sso_factory_id=$request->default_factory;
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
        //註冊各系統
        $user_id_user_name_to_json = [
            "id"=> [$request->email],
            "name" => [$request->name],
            "email" =>[$request->email],
            "pwd" =>[$request->password],
            "agent"=>[""]
        ];


        //擁有全部的權限
        if($request->default_factory=='0'){
            $get_all_system=DB::table('child_system')->get();
            $get_all_system=json_decode($get_all_system,true);
            for($i=0;$i<count($get_all_system);$i++){
                if($get_all_system[$i]['name']=='PDM'){
                    $http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'form_params'=>[
                            'user_id'=>$user->id,
                            'user_auth'=>'1'
                        ]
                    ]);
                }
                if($get_all_system[$i]['name']=='微型系統'){
                
                    $response_paas=$http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'body' => json_encode($user_id_user_name_to_json),
                    ]);
                }
            }
        }else{
            //沒有全部的權限
            $get_all_system=DB::table('child_system')->where('child_factory_id',$request->default_factory)->get();
            $get_all_system=json_decode($get_all_system,true);
            for($i=0;$i<count($get_all_system);$i++){
                if($get_all_system[$i]['name']=='PDM'){
                    $http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'form_params'=>[
                            'user_id'=>$user->id,
                            'user_auth'=>'1'
                        ]
                    ]);
                }
                if($get_all_system[$i]['name']=='微型系統'){
                
                    $response_paas=$http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'body' => json_encode($user_id_user_name_to_json),
                    ]);
                }
            }
        }
        
        

        //這邊要註冊 PDM
        // $http->post('http://pdm-new.com/api/public/api/createuser',[
        //     'verify'=>false,
        //     'form_params'=>[
        //         'user_id'=>$user->id,
        //         'user_auth'=>'1'
        //     ]
        // ]);
        // $user_id_user_name_to_json = [
        //     "id"=> [$request->email],
        //     "name" => [$request->name],
        //     "email" =>[$request->email],
        //     "pwd" =>[$request->password],
        //     "agent"=>[""]
        // ];
        // $response_paas=$http->post('https://211.20.223.148:3687/api/KMMINI/1.0/ms/CommonUse/RemoteDB/baccount?uid=@sapido@PaaS',[
        //     'verify'=>false,
        //     'body' => json_encode($user_id_user_name_to_json),
        // ]);



        return response(['data'=>json_decode((string)$response_paas->getBody(),true)]);

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
    public function loadchildwebsite(Request $request){
        $get_factory=$request->factory;
        if($get_factory!='0'){
            $get_default_system=DB::table('child_system')->select('name','url')->where('child_factory_id',$get_factory)->get();
        }else{
            $get_default_system=DB::table('child_system')->select('name','url')->get();
        }
    
        return $get_default_system;
    }
    public function loadfactory(){
        $get_factory=DB::table('sso_factory_area')->get();
        return $get_factory;
    }
}
