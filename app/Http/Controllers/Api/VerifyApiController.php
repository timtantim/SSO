<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use App\Mail\Notify_Manager;
use Illuminate\Support\Facades\Mail;

use function GuzzleHttp\json_decode;

class VerifyApiController extends Controller
{
    public function verifyuser(Request $request){
        
        $get_user_id=$request->user_id;
        //這邊要取得用戶所有可前往的資訊
        $get_user_info=DB::table('users')->where('id',$get_user_id)->get();
        $get_user_info=json_decode($get_user_info,true);
        $get_user_veryfy_system=DB::table('sso_user_child_system')
                    ->join('child_system','sso_user_child_system.child_system','child_system.id')
                    ->join('sso_admin_users','child_system.manager','sso_admin_users.id')
                    ->select('child_system.api','child_system.name','sso_admin_users.email')
                    ->distinct('child_system.name')
                    ->get();
        //註冊各系統
        $http=new Client;
        $user_id_user_name_to_json = [
          "id"=> [$get_user_info[0]['email']],
          "name" => [$get_user_info[0]['name']],
          "email" =>[$get_user_info[0]['email']],
          "pwd" =>[$get_user_info[0]['password_no_hash']],
          "agent"=>[""]
        ];
        $get_all_system=json_decode($get_user_veryfy_system,true);
            for($i=0;$i<count($get_all_system);$i++){
                if($get_all_system[$i]['name']=='PDM'){
                    $http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'form_params'=>[
                            'user_id'=>$get_user_id,
                            'user_auth'=>'1'
                        ]
                    ]);
                    Mail::to($get_all_system[$i]['email'])->send(new Notify_Manager());

                }
                if($get_all_system[$i]['name']=='微型系統'){
                
                    $response_paas=$http->post($get_all_system[$i]['api'],[
                        'verify'=>false,
                        'body' => json_encode($user_id_user_name_to_json),
                    ]);

                    Mail::to($get_all_system[$i]['email'])->send(new Notify_Manager());
                }
            }
            DB::table('users')->where('id',$get_user_id)->update(['verify'=>'1']);

        //       if($request->default_factory=='0'){
        //     $get_all_system=DB::table('child_system')->get();
        //     $get_all_system=json_decode($get_all_system,true);
        //     for($i=0;$i<count($get_all_system);$i++){
        //         if($get_all_system[$i]['name']=='PDM'){
        //             $http->post($get_all_system[$i]['api'],[
        //                 'verify'=>false,
        //                 'form_params'=>[
        //                     'user_id'=>$user->id,
        //                     'user_auth'=>'1'
        //                 ]
        //             ]);
        //         }
        //         if($get_all_system[$i]['name']=='微型系統'){
                
        //             $response_paas=$http->post($get_all_system[$i]['api'],[
        //                 'verify'=>false,
        //                 'body' => json_encode($user_id_user_name_to_json),
        //             ]);
        //         }
        //     }
        // }else{
        //     //沒有全部的權限
        //     $get_all_system=DB::table('child_system')->where('child_factory_id',$request->default_factory)->get();
        //     $get_all_system=json_decode($get_all_system,true);
        //     for($i=0;$i<count($get_all_system);$i++){
        //         if($get_all_system[$i]['name']=='PDM'){
        //             $http->post($get_all_system[$i]['api'],[
        //                 'verify'=>false,
        //                 'form_params'=>[
        //                     'user_id'=>$user->id,
        //                     'user_auth'=>'1'
        //                 ]
        //             ]);
        //         }
        //         if($get_all_system[$i]['name']=='微型系統'){
                
        //             $response_paas=$http->post($get_all_system[$i]['api'],[
        //                 'verify'=>false,
        //                 'body' => json_encode($user_id_user_name_to_json),
        //             ]);
        //         }
        //     }
        // }
    }
}
