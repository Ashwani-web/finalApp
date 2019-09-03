<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Forgotpassword; 
use Mail;
use Illuminate\Support\Facades\Auth; 
use Validator;
use JWTAuth;

use App\Social_credentials;
use Socialite;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use DB;
use Hash;

class UserController extends Controller 
{
public $successStatus = 200;



/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    // public function login(){ 
    //     if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
    //         $user = Auth::user(); 
    //         if($user){
    //         $success = "LoggedIn";
    //         return response()->json(['success' => $success], $this-> successStatus); 
    //      }
    //     } 
    //     else{ 
    //         return response()->json(['error'=>'Unauthorised'], 401); 
    //     } 
    // }

    public function login(Request $request){ 
        $credentials = $request->only('email', 'password');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid credentials'], 400);
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
                //return response()->json(['error' => 'could_not_create_token'], 500);
        return response()->json(['status' => "0",'message' => "could_not_create_token"]);

            }
            
            //return response()->json(compact('token'));
        return response()->json([compact('token'),'status' => "1",'message' => "Success"]);
    }


    public function register(Request $request)
        {  
            
           if(!isset($request->id)){


             $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required',
            'country' => 'required',
            'city' => 'required',
            ]);
            if($validator->fails()){
            //return response()->json($validator->errors()->toJson(), 400);
              // print_r($validator->errors()->all());
              // echo implode(" and ",$validator->errors()->all());
              // exit;
              $status=0;
              $message = implode(" and ",$validator->errors()->all());
              return response()->json(compact('status','message'));

               //return response()->json($validator->errors(), 400);
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
                    $user->gender = strtolower($request->gender);
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
    //         
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
                    $user->gender =  strtolower($request->gender);
                }
                if(isset($request->country)&& !empty($request->country)){
                    $user->country = $request->country;
                }
                
                if(isset($request->city)&& !empty($request->city)){
                    $user->city = $request->city;
                }
                
                $user->verified =  true;
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
                Auth::loginUsingId($user->id);
                //return response()->json(compact('user','token'),201);
                DB::table('users')
                  ->where('email', '=', ($user->email))
                  ->update(['users.appToken'=> $token]);
                $status = 1;
                $message = "Success";
                return response()->json(compact('user','token','status','message'));
               // return response()->json([compact('user','token') ,'success' => $user], $this-> successStatus); 
               
            
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
                            //return response()->json(['user_not_found'], 404);
                            return response()->json(['status' => "0",'message' => "User not found"]);
                    }

            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    //return response()->json(['token_expired']);
                    //print_r($e);
                   return $e->getMessage();
              //return $e;

             //return response()->json(['status' => "0",'message' => "Token expired"]);
          
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    //return response()->json(['token_invalid'], $e->getStatusCode());
                    return response()->json(['status' => "0",'message' => "Invalid Token"]);

            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

                    //return response()->json(['token_absent'], $e->getStatusCode());
                    return response()->json(['status' => "0",'message' => "Token absent"]);

            }

            //return response()->json(compact('user'));
            return response()->json([compact('user'),'status' => "1",'message' => "Success"]);

    }

    public function logout(Request $request)
    {
        $header = $request->header('Authorization');
        $user = JWTAuth::parseToken()->authenticate();
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
            
       $header = $request->header('Authorization');
       if ($profile = JWTAuth::parseToken()->authenticate()) {

         if($_FILES['file']['name'])
         {
            $file = $_FILES['file']['name']; 
            $name=time().$file; 
            $destinationPath = 'images/'.$name;  
            $url='http://192.168.0.25:8000/images/'.$name;
            move_uploaded_file($_FILES["file"]["tmp_name"], $destinationPath);
         }
          $profile->user_image = $url;
          $profile->save();
          return response()->json(['status' => 1,'message' => 'Successfully upload image']);
    }

     }


      /* start  user delete */

  /*public function destroy(Request $request){
     $user = User::select('id')->where('id','=',($request->id))->first();

     $user->delete();
     return response()->json(['status' => "1",'message' => "User Delete Successfully"]);
    }
    */

     //public function destroy(Request $request){
   public function destroy(){
    try{
    $user = JWTAuth::parseToken()->authenticate();
 
    DB::table("users")->where("id", $user->id)->delete();
    DB::table("goal")->where("user_id", $user->id)->delete();
    DB::table("sub_category")->where("user_id", $user->id)->delete();
    return response()->json(['status' => 1,'message' => "User Delete Successfully"]);
    }
    catch (JWTException $e) {
    return response()->json(['status' => "0",'message' => "Invalid token"]);
     }


    }
     


     /* End  user delete */



     public function forgotpassword(Request $request)
    {
        $user = User::select('email','id','first_name','last_name')
                    ->where('email','=',($request->email))->first();

      //$ashwani= $user['first_name'];

       //print_r($user['first_name']);exit;
              //print_r($request->email);exit;
         if(isset($user->email)){
           $code = bcrypt('$user->email'.'$user->last_name');
           $user_id = $user->id;
           $emails = $user->email;
           
            
           
            
           //Session::put('name', $name);

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

          
 
           Mail::send([], $data, function($message) use ($data){
           
           $username = User::select('first_name')
                    ->where('email','=',$data['email'])->first();
                    $name =$username->first_name;

           $message->to($data['email'])->subject
            ('Forgot password')->setBody('<div>'.'<h4>Hello '. $name.',' .'</h4>'."<br>".'Please click on '.$data['url']. $data['code'] . ' to reset your password.'. '</div>','text/html');
           $message->from('test.enthuons@gmail.com','Timetable');
           });
      
          return response()->json(['status' => "1",'message' => "Success",]);
          }

          else{
          return response()->json(['status' => "0",'message' => "Email not exist"]);
          }

    }



    public function changepassword(Request $request)
    {   

     $credentials = $request->only('password', 'token','newpassword');

       if ($cpassword = JWTAuth::parseToken()->authenticate()) {
         /*if (Hash::check($credentials['password'], $cpassword['password'])){

          print_r('testttt');exit;

         }*/
        //if($cpassword['password'] === $credentials['password']){

         if (Hash::check($credentials['password'] , $cpassword['password'])){

       if(isset($credentials['newpassword']) && !empty($credentials['newpassword'])){
              $cpassword['password']= bcrypt($credentials['newpassword']);
              $cpassword->save();
              return response()->json(['status' => 1,'message' => 'Successfully change your password']);

        }
        else{
        return response()->json(['status' => 0,'message' => 'Please enter new password']);

        }

        }
        else{
        return response()->json(['status' => 0,'message' => 'Password not match']);
          }
         
          }

          else{

              return response()->json(['status' => 0,'message' => 'Token invalid']);
         }

            
    }
 
}