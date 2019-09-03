<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;
Use Cache;
use Redis;
//use Illuminate\Support\Facades\Redis;

class UserdataController extends Controller
{
    //

    public function showArticle($id)
    { 
    	//$es=Auth::user()->email;
        $storage = Redis::Connection();
        $view = $storage->incr('article:'.$id.':views');

        return "This is an article with id : ". $id ." it has" . $view . "views";
 
        //return view('user.profile',compact('user'));
    }
 
 
     public function showProfile(){

       // $storage = Redis::Connection();
        


       //$user = Redis::get('user:'.$ak);
       /* $user = User::all();
                   
         return view('user.u-list',compact('user'));*/

    	/*$user = User::first()
                    ->where('email','=',(Auth::user()->email))->first();
         return view('user.profile',compact('user'));*/

         /* $user = Redis::get('users.all');

          $response = json_decode($user);*/
// $response = array('response' => json_decode($user));


          //$response = json_decode(json_encode($user), True);
          //print_r(json_decode(json_encode($user));exit;
          
          /* foreach ($response as $key => $value) {
                
           $t1[$key]['id']=$value->id;
           $t1[$key]['name']=$value->name;
           $t1[$key]['email']=$value->email;
           $t1[$key]['contact']=$value->contact;
           $t1[$key]['address']=$value->address;
           }
           //$t2['users']=$t1;
           // print_r($t2);exit;
           // return view('user.u-list', ['user' => $t2]);
         return view('user.u-list',compact('t1'));*/


         /*$user = Redis::select('users')
                    ->where('email','=',(Auth::user()->email))->first();
         return view('user.profile',compact('user'));
*/

    $id = Auth::user()->id;

    if ($response = Redis::get($id)) {

        $user = json_decode($response);
        return view('user.profile',compact('user'));
       
    }
    else{
 
    $ak = Auth::user()->email;
    $user =  User::where('email', $ak)->first();
    // store data into redis for next 24 hours
    Redis::setex($id, 60 * 60 * 24, $response);
    return view('user.profile',compact('user'));
    }

}

    public function editProfile(Request $request){
       /* $user = User::find($request->id);
        Redis::setex('users.all', 60 * 60 * 24, $user);
    	return view('user.edit_profile',compact('user'));  */

        //$response = Redis::get('users.all')->where('id',$request->id);
        $response = Redis::get('users.all');
        //$response = Redis::exists('users.all', $request->id);

        if($response){
        $user = json_decode($response);
        return view('user.edit_profile',compact('user'));  
        }
        /*else{

         $user = User::find($request->id);
         //return view('user.edit_profile',compact('user'));
        }*/


       /*  if($response = Redis::get('users.all')){

             $user = Redis::get($request->id);
             return view('user.edit_profile',compact('user')); 
        } */
    } 

    

    public function updateProfile(Request $request, $id)
    {
        //
        $user= \App\User::find($id);
        $user->name=$request->get('name');
        $user->email=$request->get('email');
        $user->contact=$request->get('contact');
        $user->address=$request->get('address');
        //return view('user.profile',compact('user'));  
        $user->save();
        return redirect('user-profile');
    }

    public function deleteProfile(Request $request){
        
         DB::table("users")->where("id", $request->id)->delete();
       
        return redirect('/')->with('success','Information has been  deleted');
    } 



    public function showProfileList(){

   
    $user =  User::all();
    // store data into redis for next 24 hours
    Redis::setex('users.all', 60 * 60 * 24, $user);
    return view('user.u-list',compact('user'));

 
}

}
