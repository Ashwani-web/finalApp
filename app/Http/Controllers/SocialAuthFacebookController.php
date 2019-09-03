<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Socialite;
use Auth;
use Exception;

class SocialAuthFacebookController extends Controller
{
     public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
//    public function callback(SocialFacebookAccountService $service)
//    {
//        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
//        auth()->login($user);
//        return redirect()->to('/home');
//    }
    
     public function callback()
   {
        try {
            
            $linkedUser = Socialite::driver('facebook')->user();
            //print_r($linkedUser);exit;
            
            $data = DB::table('social_credentials')->where('social_id',$linkedUser->id)->where('platform','facebook')->first();
            
            if($data){ 
                $existUser = User::where('id',$data->user_id)->first();
                if($existUser) {
                    $yes = Auth::loginUsingId($existUser->id);
                        if($yes){
                            $staff = User::find($existUser->id);
                            $staff->last_login =  date('Y-m-d H:i:s');
                            $staff->save();
                        }
                    $token = JWTAuth::fromUser($existUser);
                }
            }else { 
                $user = new User;
                $user->first_name = $linkedUser->user['firstName'];
                $user->last_name = $linkedUser->user['lastName'];
                
                if(isset($linkedUser->email)&& !empty($linkedUser->email)){
                    $user->email = $linkedUser->email;
                }
                
                $user->password = md5(rand(1,10000));
                
                if(isset($linkedUser->email)&& !empty($linkedUser->email)){
                    $user->user_name= $linkedUser->email;
                }
                if(isset($linkedUser->avatar)&& !empty($linkedUser->avatar)){
                    $user->user_image = $linkedUser->avatar;
                }
                if(isset($linkedUser->token)&& !empty($linkedUser->token)){
                    $user->facebook_token= $linkedUser->token;
                }
                
                if(isset($linkedUser->gender)&& !empty($linkedUser->gender)){
                    $user->gender =  'male';
                }
                if(isset($linkedUser->user['location']['country']['code'])&& !empty($linkedUser->user['location']['country']['code'])){
                    $user->country = $linkedUser->user['location']['country']['code'];
                }
                
                if(isset($linkedUser->user['location']['name'])&& !empty($linkedUser->user['location']['name'])){
                    $user->city = $linkedUser->user['location']['name'];
                }
                
                $user->verified =  true;
                $user->linkedIn_token='';
                $user->google_token='';
                $user->remember_token='';
                
                $user->save();
   
                $usr = new Social_credentials;
                $usr->user_id = $user->id;
                $usr->platform = "facebook";
                $usr->social_id = $linkedUser->id;
                $usr->social_token = $linkedUser->token;
                $usr->save();
                
                $token = JWTAuth::fromUser($user);
                Auth::loginUsingId($user->id);
                
            }
            return response()->json(compact('user','token'),201);
        } 
        catch (Exception $e) {
            return 'error';
        }
    }
    
    
}
