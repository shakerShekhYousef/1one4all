<?php

namespace App\Http\Controllers;
use Validator;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function Register(Request $request){
        $Data = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required',
            'roletype' => 'required',
            'sub_category' => 'required',
            'password' => 'required'              
            ]);
                if($Data->fails()){
                    return response()->json(['errors' => ['some fields are' => [' are required']]], 400);
                 }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->subcategory_id = $request->sub_category;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->roletype;
        $user->save();
        if ($request->roletype == 2) {
            $user->approved = false;
            $user->save();
        }

        $token = $user->createToken('Token Name')->accessToken;
        return response(['Key'=>'succes','Value'=>$token]);  
   
    }
      public function Login(Request $request){
        $Data = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'              
            ]);
                if($Data->fails()){
                    return response()->json(['errors' => ['all fields are' => [' are required']]], 400);
                 }
     $user=User::where('email',$request->email)->first();
     if(!$user || !Hash::check($request->password,$user->password)){
     return response()->json(['errors' => ['email or password' => [' is invalid.']]], 400);
      }
    $token = $user->createToken('Token Name')->accessToken;
    return response(['Key'=>'succes','Value'=>$token]);  
   
    }


    public function GetUserInfo(){
        if(!auth("api")->user())
        return response()->json(['key'=>'error not auth'],400);
    $user = User::Find(auth("api")->user()->id);
        return response()->json($user);
    }


    public function UpdateUserInfo(Request $request){
        if(!auth("api")->user())
        return response()->json(['key'=>'error not auth'],400);
    $user = User::Find(auth("api")->user()->id);

    // $user_id = auth()->user()->id;
    // $user = User::find($user_id);

    if($request->name!=null){
        $user->name=$request->name;
    }
    if($request->email!=null){
        $user->email=$request->email;
    }
    if($request->mobile!=null){
        $user->mobile=$request->mobile;
    }
   
    if($user->role_id== 2){
        if($request->sub_category!=null){
            $user->subcategory_id=$request->sub_category;
        }
    }
    $user->save();
    return response(['key'=>'success']);
    }
}
