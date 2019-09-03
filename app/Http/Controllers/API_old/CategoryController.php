<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Subcategory;
use App\Goal;
use JWTAuth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CategoryController extends Controller
{
    //

	public function categorylist(Request $request){


        
      try{
      $header = $request->header('Authorization');
      if (! $user = JWTAuth::parseToken()->authenticate()) {
       
      return response()->json(['status' => 0,'message' => "User not found"]);
     }else{

          /*$subcategory = Subcategory::select('id','name','fk_cat_id','user_id')
                    ->where('user_id', $user->id)->get();

                    $category = Category::select('id','name')->get();
                    
                     foreach($category as $cat){

                        $cat['sub_cat_count']=0;

                        foreach($subcategory as $subcat){

                            if($cat['id'] == $subcat['fk_cat_id'])

                            {
                                 $cat['sub_cat_count']+=1;
                                 //print_r($cat['sub_cat_count']);exit;
                            }

                        }


                    } */


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

 

    }
       

    public function createcategory(Request $request){


    	$category = new Category;
    	$category->name = $request->get('name');
    	$category->icon = $request->get('icon');
    	//$category->slug = $request->get('slug');
        $url = strtolower($request->get('name'));
        $category->slug = str_replace(' ', '-', $url);


    	$category->save();
    	
    	return response()->json(['status' => 1,'message' => "Success"]);

    }


    public function subcategorylist(Request $request){


        try{
            //$header = $request->header('Authorization');
        $user = JWTAuth::parseToken()->authenticate();
 
    	//$subcategory=\App\Subcategory::all();
        $subcategory = DB::table("sub_category")->where("user_id", $user->id)->get();
        $category = DB::table("category")->where("id", $$subcategory->fk_cat_id)->get();
    	
    	return response()->json(['data' => $subcategory,'category' => $category,'status' => 1,'message' => "Success"]); 
         }
         catch (JWTException $e) {
    return response()->json(['status' => 0,'message' => "Token expired"]);
     }

    }
    
   



}
