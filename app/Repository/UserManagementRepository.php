<?php

namespace App\Repository;


use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserManagementRepository
{

    public static function saveUserData($input){
        $user = null;
        if(isset($input['id'])){
            $input['id'] =  $input['id'];
            $user = User::find($input['id']);
            if (!$user){
                return "Wrong user id provided.";
            }
        }else{
            $user = new User();
        }
        if(isset($input['owner_name'])) {
            $user->owner_name = $input['owner_name'];
        }
        if(isset($input['shop_name'])) {
            $user->shop_name = $input['shop_name'];
        }
        if(isset($input['email'])) {
            $user->email = trim($input['email']);
        }
        if(isset($input['mobile_number'])) {
            $user->mobile_number = $input['mobile_number'];
        }
        if(isset($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        if(isset($input['latitude'])) {
            $user->latitude = $input['latitude'];
        }
        if(isset($input['longitude'])) {
            $user->longitude = $input['longitude'];
        }
        if(isset($input['address'])) {
            $user->address = $input['address'];
        }

        $user->save();
        return $user;
    }
}
