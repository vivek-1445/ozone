<?php

namespace App\Repository;


use App\Models\User;


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
        if(isset($input['username'])) {
            $user->username = $input['username'];
        }
        if(isset($input['email'])) {
            $user->email = $input['email'];
        }
        if(isset($input['phone'])) {
            $user->phone = $input['phone'];
        }
        if(isset($input['points'])) {
            $user->points = $input['points'];
        }
        if(isset($input['profile_image'])) {
            $user->profile_image = CommonHelper::uploadFile($input['profile_image'], 'uploads/user');
        }else{
            if(!isset($user->profile_image)){
                $user->profile_image = 'images/default-profile-pic.jpeg';
            }
        }
        $user->trial_date = date("Y-m-d H:i:s", strtotime("+14 day"));

        $user->save();
        return true;
    }
}
