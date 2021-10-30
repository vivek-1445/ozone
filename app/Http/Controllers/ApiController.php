<?php

namespace App\Http\Controllers;


use App\Mail\RegisterEmail;
use App\Models\User;
use App\Repository\UserManagementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register()
    {
        $input = request()->all();
        $rules = array(
            'shop_name' => 'required',
            'owner_name' => 'required',
            'mobile_number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required',
            'email' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $email_exist = User::where('email', '=', $input['email'])->exists();
        $phone_exist = User::where('mobile_number', '=', $input['mobile_number'])->exists();
        if ($email_exist || $phone_exist) {
            if ($email_exist) {
                return response()->json(['message' => 'Email already exist'], 401);
            } else {
                return response()->json(['message' => 'Phone number already exist'], 401);
            }
        } else {
            $data = UserManagementRepository::saveUserData($input);
            if ($data) {
                $otp = rand(100000, 999999); // a random 6 digit number
                $data->verify_code = "$otp";
                $data->otp_expire = time() + 600;
                $data->save();
                $emailuser = str_replace("xE2x80x8B", "", $input['email']);
                Mail::to($emailuser)->send(new RegisterEmail($data));
                return response()->json(['message' => 'We have sent OTP on your Email.']);
            } else {
                return response()->json(['message' => 'Data Does Not Saved'], 401);

            }
        }
    }

    public function login()
    {
        $input = request()->all();
        $rules = array(
            'email' => 'required',
            'password' => 'required'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $credentials = request(['email', 'password']);
        if (!$token = $this->guard()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public function forgotPassword()
    {
        $input = request()->all();

        $rules = array(
            'email' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $email_exist = User::where('email', '=', $input['email'])->exists();
        if ($email_exist) {
            return response()->json(['message' => 'Email send on your registered email'], 401);
        }else {
            return response()->json(['message' => 'Email does not exist'], 401);
        }
    }

    public function resendOtp()
    {
        $input = request()->all();

        $rules = array(
            'mobile_number' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $email_exist = User::where('mobile_number', '=', $input['mobile_number'])->exists();
        if ($email_exist) {
            return response()->json(['message' => 'Otp sent on your registered mobile number'], 401);
        }else {
            return response()->json(['message' => 'Mobile number does not exist'], 401);
        }
    }

    public function verifyEmail()
    {
        $input = request()->all();
        $rules = array(
            'email' => 'required',
            'verify_code'=>'required'
        );
        $validator = Validator::make($input, $rules);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first()],401);
        }
        $user = User::where('email',$input['email'])->where('verify_code',$input['verify_code'])->where('status',0)->first();
        if(!empty($user)){
            $user->verify_code = null;
            $user->otp_expire =null;
            $user->status = 2;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();
            return response()->json(['message' => 'Your account is verified.Please log in.']);
        }else{
            return response()->json(['message' => 'You have entered wrong verify code.'], 401);
        }
    }

    public function addProduct(){
        dd(Auth::id());
    }
}
