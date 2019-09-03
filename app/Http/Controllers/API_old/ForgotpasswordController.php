<?php

namespace App\Http\Controllers\API;
use App\User; 
use App\Forgotpassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ForgotpasswordController extends Controller
{
    //


public function index(Request $request){
 
        // print_r($_GET);exit;
      $forgotpassword = Forgotpassword::select('code','user_id')
                    ->where('code','=',($_GET['v']))->first();
       
          //print_r($forgotpassword->id);exit;

           if($forgotpassword==!""){
           $users = User::select('email','id')
                    ->where('id','=',($forgotpassword->user_id))->first();

          $email = $users->email;
          $id = $users->id;

          /*DB::table('forgot_password')->where('forgot_password.user_id', '=', $id)->delete();*/

           return view('auth/passwords/resetpassword',['email' => $email],['id' => $id]);
           }
           else{
         
                 //print_r('Your session has been expired');
                 print_r('<h3 style="text-align:center;margin-top:20px;">Your session has been expired</h3><div>');

           }
 
    }
    public function resetpassword(Request $request){

  
    
 $user = User::select('email','password','id')
                    ->where('email','=',($request->email))->first();

 
if($user['email'] == $request->email){

    if ($request->password === $request->confirm_password) {

      DB::table('users') ->where("users.email", '=', $request->email) ->update(['users.password'=> bcrypt($request->password)]);


      DB::table('forgot_password')->where('forgot_password.user_id', '=', $user->id)->delete();
         
        
     print_r('<h3 style="text-align:center;margin-top:20px;">Password Updated Successful</h3><div>');
      //print_r($user['id']);exit;

 
   
     }
  else {
   print_r('<h3 style="text-align:center;margin-top:20px;">Password and confirm password not match</h3><div>');
     }
     }
  else{

   print_r('<h3 style="text-align:center;margin-top:20px;">Email not exist</h3><div>');

 }




/*DB::table('users') ->where("users.email", '=', $request->email) ->update(['users.password'=> $request->password]);*/
 
 
            //return view('auth/passwords/resetpassword');

        // }


    }



}

