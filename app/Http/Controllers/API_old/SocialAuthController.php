<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Social_credentials;
use Socialite;
use Exception;
use DB;

class SocialAuthController extends Controller
{
    public $successStatus = 200;
    //public $message = No_record_found;
    
    public function login(Request $request){ 
        $credentials = $request->only('email', 'password');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }else{
                    $existUser = User::where('email',$credentials['email'])->first();
                    if($existUser) {
                        $staff = User::find($existUser->id);
                        $staff->last_login =  date('Y-m-d H:i:s');
                        $staff->save();
                    }
                }
            }
            catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            
            return response()->json(compact('token'));
    }


    public function register(Request $request)
        { 
            //print_r($request);exit;
        $linkedUser = $request->only('id', 'first_name','last_name','platform','token');
        
        try {
            
//            $linkedUser = Socialite::driver('facebook')->user();
            
            $data = DB::table('social_credentials')->where('social_id',$linkedUser['id'])->where('platform',$linkedUser['platform'])->first();
            
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

                
                return response()->json(['status' => "0",'message' => "User not exist" ]);

                /*$user = new User;
                $user->first_name = $linkedUser['first_name'];
                $user->last_name = $linkedUser['last_name'];
                
                if(isset($linkedUser['email'])&& !empty($linkedUser['email'])){
                    $user->email = $linkedUser['email'];
                }
                
                $user->password = md5(rand(1,10000));
                
                if(isset($linkedUser['email'])&& !empty($linkedUser['email'])){
                    $user->user_name= $linkedUser['email'];
                }
                if(isset($linkedUser['avatar'])&& !empty($linkedUser['avatar'])){
                    $user->user_image = $linkedUser['avatar'];
                }
                
                
                if(isset($linkedUser['gender'])&& !empty($linkedUser['gender'])){
                    $user->gender =  $linkedUser['gender'];
                }
                if(isset($linkedUser['country'])&& !empty($linkedUser['country'])){
                    $user->country = $linkedUser['country'];
                }
                
                if(isset($linkedUser['city'])&& !empty($linkedUser['city'])){
                    $user->city = $linkedUser['city'];
                }
                
                $user->verified =  true;
                $user->image_type = $linkedUser['platform'];
                if($linkedUser['platform'] == 'linkedIn'){
                    $user->linkedIn_token= $linkedUser['token'];
                }
                if($linkedUser['platform'] == 'facebook'){
                    $user->facebook_token= $linkedUser['token'];
                }
                $user->image_type = $linkedUser['platform'];
                $user->remember_token='';
                
                $user->save();
   
                $usr = new Social_credentials;
                $usr->user_id = $user->id;
                $usr->platform = $linkedUser['platform'];
                $usr->social_id = $linkedUser['id'];
                $usr->social_token = $linkedUser['token'];
                $usr->save();
                
                $token = JWTAuth::fromUser($user);
                Auth::loginUsingId($user->id);*/
                
            }
            //return response()->json(compact('user','token'),201);
            //return response()->json([compact('token'),'status' => "1",'message' => "Success"]);
                $status = 1;
                $message = "Success";
                return response()->json(compact('token','status','message'));
        } 
        catch (Exception $e) {
            return 'error';
        }
    }


/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }

    public function logout()
    {
        auth()->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}
