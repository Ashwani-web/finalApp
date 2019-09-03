<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use Mail; 
use DB;
use Illuminate\Support\Facades\Auth; 
use Validator;
use JWTAuth;
use App\Forgotpassword; 
use App\Social_credentials;
use Socialite;
use Hash;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     

    public function login(Request $request){ 
        $credentials = $request->only('email', 'password');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    
                    return response()->json(['status' => 0,'message' => 'invalid credentials'], 400);
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
               
                return response()->json(['status' => 0,'message' => "could_not_create_token"]);
            }
           

            DB::table('users')
                ->where('email', '=', ($request->email))
                ->update(['users.appToken'=> $token]);
            $data = User::select('id','first_name','last_name')
            ->where('appToken', '=', $token)->first();
            $status = 1;
            $message = "Success";
            return response()->json(compact('token','data','status','message'));
    }


    public function register(Request $request)
        { 
           if(!isset($request->id)){

             $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100|alpha',
            //'last_name' => 'required|string|max:100|alpha_dash',
            'last_name' => 'required|string|max:100|alpha',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required',
            'country' => 'required',
            'city' => 'required',
            ]);
            if($validator->fails()){
            
            $status=0;
            $message = implode(" and ",$validator->errors()->all());
            return response()->json(compact('status','message'));

             }


            
            $user = new User;
                $user->first_name = $request->get('first_name');
                $user->last_name = $request->get('last_name');
                $user->email = $request->get('email');
                $user->user_name = $request->get('email');
                $user->image_type = 'inApp';
                $user->password = bcrypt($request->get('password'));
                $user->verified = FALSE;
                if(isset($request->gender) && !empty($request->gender)){
                    $user->gender = $request->gender;
                }
                if(isset($request->country) && !empty($request->country)){
                    $user->country = $request->country;
                }
                if(isset($request->city) && !empty($request->city)){
                    $user->city = $request->city;
                }
                if(isset($request->user_image) && !empty($request->user_image)){
                    $user->user_image = $request->user_image;
                }
                 $user->save();
                $token = JWTAuth::fromUser($user);
    
                DB::table('users')
                  ->where('email', '=', ($user->email))
                  ->update(['users.appToken'=> $token]);
                $status = 1;
                $message = "Success";
                return response()->json(compact('user','token','status','message'));
            }else{
                $user = new User;
                $user->first_name =$request->first_name;
                $user->last_name = $request->last_name;
                if(isset($request->email)&& !empty($request->email)){
                    $user->email = $request->email;
                }
                
                $user->password = md5(rand(1,10000));
                
                if(isset($request->email)&& !empty($request->email)){
                    $user->user_name= $request->email;
                }
                if(isset($request->avatar)&& !empty($request->avatar)){
                    $user->user_image = $request->avatar;
                }
                
                
                if(isset($request->gender)&& !empty($request->gender)){
                    $user->gender =  $request->gender;
                }
                if(isset($request->country)&& !empty($request->country)){
                    $user->country = $request->country;
                }
                
                if(isset($request->city)&& !empty($request->city)){
                    $user->city = $request->city;
                }
                
                $user->verified =  FALSE;
                $user->image_type = $request->platform;
                if($request->platform == 'linkedIn'){
                    $user->linkedIn_token= $request->token;
                }
                if($request->platform == 'facebook'){
                    $user->facebook_token= $request->token;
                }
                $user->image_type = $request->platform;
                $user->remember_token='';
                
                $user->save();
   
                $usr = new Social_credentials;
                $usr->user_id = $user->id;
                $usr->platform =$request->platform;
                $usr->social_id = $request->id;
                $usr->social_token = $request->token;
                $token = JWTAuth::fromUser($user);
                $user->appToken = $token;
                $usr->save();
                
                $token = JWTAuth::fromUser($user);
                Auth::loginUsingId($user->id);
               
                DB::table('users')
                  ->where('email', '=', ($user->email))
                  ->update(['users.appToken'=> $token]);
                $status = 1;
                $message = "Success";
                return response()->json(compact('user','token','status','message'));
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
                           
                        return response()->json(['status' => 0,'message' => "User not found"]);
                    }

            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['status' => 0,'message' => "Token expired"]);

            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['status' => 0,'message' => "Invalid Token"]);

            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['status' => 0,'message' => "Token absent"]);

            }

                $status = 1;
                $message = "Success";
                return response()->json(compact('user','status','message'));
    }

    public function logout(Request $request)
    {
      try {
        $header = $request->header('Authorization');
        if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 0,'message' => "User not found"]);
        }
        auth()->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        if(isset($user) && !empty($user)){
          DB::table('users')
        ->where('id', '=', ($user->id))
        ->update(['users.appToken'=> '']);
          $result = ['message' => 'Successfully logged out','status' => 1];
        }else{
          $result = ['message' => 'Invalid Token','status' => 0];
        }
        
        return response()->json($result);
      }
      catch (JWTException $e) {
    return response()->json(['status' => 0,'message' => "Token expired"]);
     } 

    }
    
    
    public function update(Request $request)
    {
        $user = User::find($request->id);
        if(isset($request->first_name) && !empty($request->first_name)){
            $user->first_name = $request->first_name;
        }
        if(isset($request->last_name) && !empty($request->last_name)){
            $user->last_name = $request->last_name;
        }
        if(isset($request->password) && !empty($request->password)){
            $user->password = $request->password;
        }
        if(isset($request->gender) && !empty($request->gender)){
            $user->gender = $request->gender;
        }
        if(isset($request->country) && !empty($request->country)){
            $user->country = $request->country;
        }
        if(isset($request->city) && !empty($request->city)){
            $user->city = $request->city;
        }
         
        $user->save();
        $status = 1;
        $message = "Success";
         
        return response()->json(compact('user','status','message'));
    }

    public function updateprofileimage(Request $request)
     {

      $validator = Validator::make($request->all(), [
            'file' => 'required',
             ]);
            if($validator->fails()){
              $status=0;
              $message = implode(" and ",$validator->errors()->all());
              return response()->json(compact('status','message'));

             }
         try{   
       $header = $request->header('Authorization');
       if ($profile = JWTAuth::parseToken()->authenticate())
        {

         if($_FILES['file']['name'])
         {
            $file = $_FILES['file']['name']; 
            $name=time().$file; 
            $destinationPath = 'images/'.$name;  
            $url='http://timetable.codesk.work/images/'.$name;
            move_uploaded_file($_FILES["file"]["tmp_name"], $destinationPath);
         }
          $profile->user_image = $url;
          $profile->save();
          return response()->json(['status' => 1,'message' => 'Successfully upload image']);
         }
        }
        catch (JWTException $e) {
       return response()->json(['status' => 0,'message' => "Token expired"]);
     }
    
     }



     public function destroy(){

       try{
       $user = JWTAuth::parseToken()->authenticate();
       DB::table("users")->where("id", $user->id)->delete();
       DB::table("goal")->where("user_id", $user->id)->delete();
       DB::table("sub_category")->where("user_id", $user->id)->delete();
       return response()->json(['status' => 1,'message' => "User Delete Successfully"]);
   }
    catch (JWTException $e) {
    return response()->json(['status' => 0,'message' => "Token expired"]);
     }  
     }


     public function forgotpassword(Request $request){
        
          
        $user = User::select('email','id','last_name')
                    ->where('email','=',($request->email))->first();
           if(isset($user->email)){
           $code = bcrypt('$user->email'.'$user->last_name');
           $user_id = $user->id;
           $emails = $user->email;
          
           $values = array('code' => $code,'user_id' => $user_id,
            'created_at' => now(),'updated_at' => now());

            /* Start check user id in forgot pasword table*/
           $oldpass = Forgotpassword::select('user_id')
                    ->where('user_id','=',$user_id)->first();
           if($oldpass['user_id'] == $user_id ){
           DB::table('forgot_password')->where('forgot_password.user_id', '=', $user_id)->delete();
            }
            /* End check user id in forgot pasword table*/
           DB::table('forgot_password')->insert($values);
           $data = array('email'=>$emails,'code'=>$code,'url'=>"http://192.168.0.25:8000/api/forgot-password-url?v=");

           $link = $data['url']. $data['code'];


         Mail::send([], $data, function($message) use ($data){
 
          $username = User::select('first_name')
                    ->where('email','=',$data['email'])->first();
                    $name =$username->first_name;

          $link = url($data['url'].$data['code']);
//print_r($link);exit;
          /*$message->to($data['email'])->subject
            ('Forgot password')->setBody('<div>'.'<h4>Hi'. $name.',' .'</h4>'."<br>".'Please click on '.$data['url']. $data['code'] . ' to reset your password.'. '</div>','text/html');
           $message->from('test.enthuons@gmail.com','Timetable');
           });*/

           $message->to($data['email'])->subject
            ('Forgot password')->setBody('<div>'.'<div style=text-align:center;>'.'<img style="width:160px;" src="http://192.168.0.25:8000/logo/logo.png">'.'</div>'.'<h3 style=color:#000>'.'<span style=padding-right:10px;>Hi</span>'. $name.',' .'</h3>'."<br>"."<div style=font-size:22px;color:#000;>"."We've received a request to reset your password. If you didn't make the request , just ignore this email. Otherwise ,you can reset your password using this link:"."</div>"."<a href=$link style=background-color:#2a89fa;text-decoration:none;padding:20px;margin-top:20px;display:block;color:#fff;text-align:center;font-weight:900;>"."<span style=padding:12px;font-size:20px;color:#fff;margin:70px;>"."Click here to reset your password"."</span>"."</a>".'</div>','text/html');
           $message->from('test.enthuons@gmail.com','Timetable');
           });


      
        return response()->json(['status' => 1,'message' => " Please check your email to set new password.",]);
        }

        else{
   
          return response()->json(['status' => "0",'message' => "Email not exist"]);

            }

      }
    


    
     public function changepassword(Request $request){   
             $credentials = $request->only('password','newpassword');
             $header = $request->header('Authorization');
             if ($cpassword = JWTAuth::parseToken()->authenticate()){
              
             if (Hash::check($credentials['password'] , $cpassword['password'])){

             if(isset($credentials['newpassword']) && !empty($credentials['newpassword'])){
         
             $cpassword['password']= bcrypt($credentials['newpassword']);
             $cpassword->save();
             return response()->json(['status' => 1,'message' => 'Successfully change your password']);

               }else{
              
             return response()->json(['status' => 0,'message' => 'Please enter new password']);
           }

            }else{

             return response()->json(['status' => 0,'message' => 'Password not match']);
          }
         
          }
          else{

              return response()->json(['status' => 0,'message' => 'Token invalid']);
         }

  
    }








}