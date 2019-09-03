<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Subcategory;
use App\Goal;
use App\Goalstatus;
use JWTAuth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CategoryController extends Controller
{
    //

    /*public function categorylist(Request $request){
         
         try{
      $header = $request->header('Authorization');
      if (! $user = JWTAuth::parseToken()->authenticate()) {
       
      return response()->json(['status' => 0,'message' => "User not found"]);
     }else{

          

                    $goals = Goal::select('cat_id','total_seconds','user_id')
                    ->where('user_id', $user->id)->get();
                    $totaltime = $goals->sum('total_seconds'); 
                    $category = Category::select('id','name')->get();
                    foreach($category as $cat){
                       $cat['total_seconds']=0;
                          foreach($goals as $goal){
                            if($cat['id'] == $goal['cat_id'])
                            {
                                 $cat['total_seconds']+=$goal->total_seconds;
                                
                            }

                        }
                     } 

                     
         return response()->json(['total_seconds' => $totaltime ,'data' => $category,'status' => 1,'message' => "Success"]); 
         }
        }
         catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }


    }*/

    public function categorylist(Request $request){
         
         try{
      $header = $request->header('Authorization');
      if (! $user = JWTAuth::parseToken()->authenticate()) {
       
      return response()->json(['status' => 0,'message' => "User not found"]);
     }else{

        //print_r($request->start_time);exit;

        if(!empty($request->start_time)  && !empty($request->end_time)){

     $goalstatus = Goalstatus::select('cat_id','total_seconds','user_id')
        ->where('user_id', $user->id)
        ->where('goal_status.start_time', '>=' , $request->start_time)
        ->where('goal_status.end_time','<=', $request->end_time)
        ->get();
                    $totaltime = $goalstatus->sum('total_seconds'); 
                    $category = Category::select('id','name')->get();
                    foreach($category as $cat){
                       $cat['total_seconds']=0;
                          foreach($goalstatus as $goals){
                            if($cat['id'] == $goals['cat_id'])
                            {
                                 $cat['total_seconds']+=$goals->total_seconds;
                                
                            }

                        }
                     } 

                     
         return response()->json(['total_seconds' => $totaltime ,'data' => $category,'status' => 1,'message' => "Success"]); 
         }
         else{
      return response()->json(['status' => 0,'message' => "Please Select time"]); }
     }
        }
         catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }


    }

    public function subcategorylist(Request $request){

        try{
      $header = $request->header('Authorization');
      if (! $user = JWTAuth::parseToken()->authenticate()) {
       
      return response()->json(['status' => 0,'message' => "User not found"]);
     }
    $subcategory = DB::table("sub_category")->where("user_id", $user->id)->get();
    $category = DB::table("category")->select('id','name')->get();

    // if(!empty($subcategory)){
    //     return response()->json(['data' => $subcategory,'category' => $category,'status' => 1,'message' => "Success"]);
    // }
    // else{

    //     return response()->json(['status' => 1,'message' => "Sub category not exit"]);
    // }



        }
        catch (JWTException $e) {
        return response()->json(['status' => 0,'message' => "Token expired"]);
     }
    }
     
   



}
