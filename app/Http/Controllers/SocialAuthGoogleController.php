<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Socialite;
use Auth;
use Exception;
class SocialAuthGoogleController extends Controller
{
public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }


    public function callback()
    {
        try {
            
        
            $googleUser = Socialite::driver('google')->user();
            $existUser = User::where('email',$googleUser->email)->first();
            
            if($existUser) {
                $yes = Auth::loginUsingId($existUser->id);
                    if($yes){
                    $staff = User::find($existUser->id);
                    $staff->last_login =  date('Y-m-d H:i:s');
                    $staff->save();
                    }
            }
            else { 
                $user = new User;
                $user->first_name = $googleUser->user['given_name'];
                $user->last_name = $googleUser->user['family_name'];
                $user->email = $googleUser->email;
//                $user->social_id = $googleUser->id;
                $user->password = md5(rand(1,10000));
                $user->user_name= $googleUser->email;
                $user->user_image = $googleUser->avatar;
                $user->google_token = $googleUser->token;
//                $user->image_type = 'inApp';
                
                $user->gender =  'male';
                $user->country = '';
                $user->city = '';
                $user->facebook_token='';
                $user->linkedIn_token = '';
                $user->remember_token='';
//                print_r($user);exit;
                $user->save();
                Auth::loginUsingId($user->id);
            }
            return redirect()->to('/home');
        } 
        catch (Exception $e) {
            return 'error';
        }
    }
}
