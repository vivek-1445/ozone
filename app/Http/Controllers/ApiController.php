<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Repository\UserManagementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(){
        $input = request()->all();
        $rules = array(

            'email' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $email_exist = User::where('email', '=', $input['email'])->exists();
        $phone_exist = User::where('mobile', '=', $input['mobile'])->exists();
        if ($email_exist || $phone_exist){
            if ($email_exist){
                return response()->json(['message' => 'Email already exist'], 401);
            }else{
                return response()->json(['message' => 'Phone number already exist'], 401);
            }
        }else {

                $data= ['name'=>'vivek'];
                $user = $input['email'];
                Mail::send('verification',$data,function ($messages) use ($user){
                    $messages->to($user);
                    $messages->subject('test');
                });
            return response()->json(['message' => 'mailsend']);
        }

    }
    public function login(){
        dd(12);
    }
}
