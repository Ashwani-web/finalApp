<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Goal;
use DB;
use JWTAuth;
use Validator;
use Illuminate\Support\Facades\Auth; 
use App\Subcategory;
use App\Task;
use App\Goalstatus;
use App\Subjectivegoal;
use App\Category;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class GoalController extends Controller
{
    
    public function creategoal(Request $request){
     try{
      $header = $request->header('Authorization');
      if(! $user = JWTAuth::parseToken()->authenticate()){
        return response()->json(['status' => 0,'message' => "User not found"]);
      }
 
       $goalexist = Goal::select('user_id', 'cat_id')
            ->where('user_id', '=', ($user->id))
            ->where('cat_id', '=', ($request->cat_id))
            ->first();
            //print_r($goalexist);exit;

        if(!empty($goalexist)){ 

        DB::table('goal')
        ->where('user_id', '=', ($user->id))
        ->where('cat_id', '=', ($request->cat_id))
        ->update(['goal.total_seconds'=> $request->total_seconds]);
        return response()->json(['status'=>1,'message'=>"Success"]);
 
        }
       else{
      $goal = new Goal;
      $goal->user_id = $user->id;
      $goal->cat_id=$request->cat_id;
      $goal->total_seconds=$request->total_seconds;
      $goal->save();

      return response()->json(['status'=>1,'message'=>"Success"]);

       }
      }
      catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }

      }


      public function searchsubcategory(Request $request){
        

      try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }
       
       $subcategory = Subcategory::select('id','name')->where('name','LIKE','%'.$request->q."%")->get();

       //$subcategory=DB::table('sub_category')->where('name','LIKE','%'.$request->q."%")->get();
      
       if($subcategory)
         {
            foreach ($subcategory as $value) {

              $value->name;

             
          } 

          if(!empty($value->name)){
          $status = 1;
          $message = "Success";
          return response()->json(compact('subcategory','status','message'));
           }
           else{
            /*return response()->json(['subcategory','status' => 0,'message' => "Success"]);*/
          $status = 1;
          $message = "Success";
          return response()->json(compact('subcategory','status','message'));

            /*$subcategory = new Subcategory;
            $subcategory->user_id = $user->id;
            $subcategory->fk_cat_id = $request->get('cat_id');
            $subcategory->name = $request->get('name');
            $subcategory->icon = $request->get('icon');
            $url = strtolower($request->get('name'));
            $subcategory->slug = str_replace(' ', '-', $url);
            $subcategory->save();
            return response()->json(['status' => 1,'message' => "Subcategory created Successfully"]);*/

           }


         }
         else{

          return response()->json(['status' => 0,'message' => "Subcategory not exist"]);
         }
          
          }
         
         catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }



      }


      /*public function createtaskq(Request $request){
      $header = $request->header('Authorization');
      $user = JWTAuth::parseToken()->authenticate();
      $task = new Task;
      $task->cat_id = $request->cat_id;
      $task->sub_cat_id=$request->sub_cat_id;
      $task->user_id= $user->id;
      $task->total_seconds=$request->total_seconds;
      $task->save();
      return response()->json(['status'=>1,'message'=>"Success"]);
      }*/

      public function createtask(Request $request){



      try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }
       $tsk = DB::table('task')
           ->where('user_id', $user->id)
           ->where('cat_id', $request->cat_id)
           ->where('sub_cat_id', $request->sub_cat_id)
           ->get();
           foreach($tsk as $value){
                      $value;
             }
                    
       if(!empty($value)){ 

        DB::table('task')
        ->where('task.cat_id', '=', $request->cat_id)
        ->where('task.sub_cat_id' , '=', $request->sub_cat_id)
        ->where('user_id', $user->id)
        ->update(['task.total_seconds'=> $request->total_seconds]);
        return response()->json(['status'=>1,'message'=>"Success"]);
 
        }
       
       else{
          if(empty($request->sub_cat_id)){
            $subcategory = new Subcategory;
            $subcategory->user_id = $user->id;
            $subcategory->fk_cat_id = $request->cat_id;
            $subcategory->name = $request->name;
            $subcategory->icon = 'test';
            $url = strtolower($request->name);
            $subcategory->slug = str_replace(' ', '-', $url);
            $subcategory->save();
            // return response()->json(['status' => 1,'message' => "Subcategory created Successfully"]);
            // $status = 1;
            // $message = "Success";
            // return response()->json(compact('subcategory','status','message'));
            
             
            $subcategorys = DB::table('sub_category')->where("user_id", $user->id)->latest()->first();
             $task = new Task;
             $task->cat_id = $subcategorys->fk_cat_id;
             $task->sub_cat_id=$subcategorys->id;
             $task->user_id= $user->id;
             $task->total_seconds=$request->total_seconds;
             $task->save();
             
             $status = 1;
             $message = "Success";
             return response()->json(compact('task','status','message'));

            }

            else{

             $task = new Task;
             $task->cat_id = $request->cat_id;
             $task->sub_cat_id=$request->sub_cat_id;
             $task->user_id= $user->id;
             $task->total_seconds=$request->total_seconds;
             $task->save();
             return response()->json(['status'=>1,'message'=>"Success"]);
             }
      
       }

     }

     catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }
 
      }



      public function createtime(Request $request){

       try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){
       return response()->json(['status' => 0,'message' => "User not found"]);
       }
      
      $validator = Validator::make($request->all(), [
            'cat_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_seconds' => 'required',
             ]);
            if($validator->fails()){
              $status=0;
              $message = implode(" and ",$validator->errors()->all());
              return response()->json(compact('status','message'));

             }

      $goalstatus = new Goalstatus;
      if(!empty($request->sub_cat_id)){

        $task = DB::table("task")->where("user_id", $user->id)
        ->where("cat_id", $request->cat_id)
        ->where("sub_cat_id", $request->sub_cat_id)->first();
        
      $goalstatus->cat_id = $request->cat_id;
      $goalstatus->sub_cat_id=$request->sub_cat_id;
      $goalstatus->start_time = $request->start_time;
      $goalstatus->user_id = $user->id;
      $goalstatus->end_time = $request->end_time;
      $goalstatus->total_seconds=$request->total_seconds;
      if(empty($task->total_seconds)){
      $goalstatus->allocated_st = 0;
      }else{$goalstatus->allocated_st = $task->total_seconds;}
      $goalstatus->note=$request->note;
      $goalstatus->save();
      return response()->json(['status'=>1,'message'=>"Success"]);
    }
    else{

      $goal = DB::table("goal")->where("user_id", $user->id)
        ->where("cat_id", $request->cat_id)->first();
      $goalstatus->cat_id = $request->cat_id;
      //$goalstatus->sub_cat_id=$request->sub_cat_id;
      $goalstatus->sub_cat_id=0;
      $goalstatus->start_time = $request->start_time;
      $goalstatus->user_id = $user->id;
      $goalstatus->end_time = $request->end_time;
      $goalstatus->total_seconds=$request->total_seconds;
      if(empty($goal->total_seconds)){
      $goalstatus->allocated_st = 0;
      }else{$goalstatus->allocated_st=$goal->total_seconds;}
      $goalstatus->note=$request->note;
      $goalstatus->save();
      return response()->json(['status'=>1,'message'=>"Success"]);


    }
 
       }
        catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }


      }

      public function updatecreatetime(Request $request){

       try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }

        $goalstatus = Goalstatus::find($request->id)->where('user_id',$user->id)->where('id',$request->id)->first();

        
        if($goalstatus){
        if(isset($request->cat_id) && !empty($request->cat_id)){
            $goalstatus->cat_id = $request->cat_id;
        }
        if(isset($request->sub_cat_id) && !empty($request->sub_cat_id)){
            $goalstatus->sub_cat_id = $request->sub_cat_id;
        }
        if(isset($request->start_time) && !empty($request->start_time)){
            $goalstatus->start_time = $request->start_time;
        }
        if(isset($request->end_time) && !empty($request->end_time)){
            $goalstatus->end_time = $request->end_time;
        }
        if(isset($request->total_seconds) && !empty($request->total_seconds)){
            $goalstatus->total_seconds = $request->total_seconds;
        }
        if(isset($request->allocated_st) && !empty($request->allocated_st)){
            $goalstatus->allocated_st = $request->allocated_st;
        }
        if(isset($request->note) && !empty($request->note)){
            $goalstatus->note = $request->note;
        }

        $goalstatus->save();
        $status = 1;
        $message = "Success";
         
        return response()->json(compact('goalstatus','status','message'));
      }
      else{

        return response()->json(['status' => 0,'message' => "Error"]);
      }
    }
    catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }
 

      }



      public function timestatus(Request $request){
       try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){
         return response()->json(['status' => 0,'message' => "User not found"]);
       }


      /*$goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->join('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                 //->where('goal_status.user_id', $user->id)
                 ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=' , $request->start_time)->where('goal_status.end_time','<=', $request->end_time)
                 ->get();
                 

                 if(!empty($goalstatus)){
                 return response()->json(['data' => $goalstatus,'status' => 1,'message' => "Success"]);
                 }
                 else{
                  return response()->json(['status' => 0,'message' => "No value"]);
                 }

                 }

              catch (JWTException $e) {
              return response()->json(['status' => 0,'message' => "Token expired"]);
        }*/


        else{

    if (empty($request->start_time)  && empty($request->end_time)){
    
    $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                 ->where('goal_status.user_id', $user->id)
                 ->get();
      
    //return response()->json(['data' => $goalstatus,'status' => 1,'message' => "Success"]); 
    }
    elseif (!empty($request->start_time)  && empty($request->end_time)){
    
    $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->join('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                 //->where('goal_status.user_id', $user->id)
                 ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=' , $request->start_time)
                 ->get();
      
    //return response()->json(['data' => $goalstatus,'status' => 1,'message' => "Success"]); 
    }





   else{
   $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->join('sub_category','goal_status.sub_cat_id','=','sub_category.id')
    ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=' , $request->start_time)->where('goal_status.end_time','<=', $request->end_time)->get();

    //return response()->json(['data' => $goalstatus,'status' => 1,'message' => "Success"]);
  }

    }
    foreach ($goalstatus as $key => &$value) {
          $value->start_time = intval($value->start_time);
          $value->end_time = intval($value->end_time);
          $goalstatus[$key] =  $value;
      }
return response()->json(['data' => $goalstatus,'status' => 1,'message' => "Success"]); 
    }

}
catch (JWTException $e) {
              return response()->json(['status' => 0,'message' => "Token expired"]);
        }
 

 
      }


      public function createsubjectivegoal(Request $request){

         try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }
        

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            ]);
            if($validator->fails()){
              $status=0;
              $message = implode(" and ",$validator->errors()->all());
              return response()->json(compact('status','message'));

             }

        $subjectivegoal = new Subjectivegoal;
        $subjectivegoal->user_id = $user->id;
        $subjectivegoal->name=$request->name;
        $subjectivegoal->description= $request->description;
        $subjectivegoal->save();

      return response()->json(['status'=>1,'message'=>"Success"]);
       }

       catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }


      }

      public function subjectivegoallist(Request $request){
        
         try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }

        $subjectivegoal = DB::table("subjective_goal")->where("user_id", $user->id)->get();

        $status = 1;
        $message = "Success";
        return response()->json(compact('subjectivegoal','status','message'));



       }

     catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }




      }


      public function listoftime11(Request $request){


        //$header = $request->header('Authorization');
        //$user = JWTAuth::parseToken()->authenticate();

         /*$goals = Task::select('task.total_seconds','task.user_id','task.sub_cat_id','goal.cat_id','goal.total_seconds', 'category.name','sub_category.name AS sub_cat_name')
                 ->join('goal','task.cat_id','=','goal.cat_id')
                 ->join('category','goal.cat_id','=','category.id')
                 ->join('sub_category','task.sub_cat_id','=','sub_category.id')
                 //->groupBy('cat_id')
                 ->where('task.user_id', $user->id)
                 ->get();*/


                  /*$goal = DB::table('task')
->join('goal', 'goal.cat_id', '=', 'task.cat_id')
->select('goal.cat_id', 'goal.total_seconds')
->groupBy('cat_id')
->get();

dd($goal);
*/
    
                  
        $header = $request->header('Authorization');
        $user = JWTAuth::parseToken()->authenticate();

$goals = Task::select('task.total_seconds','task.user_id','task.sub_cat_id','goal.cat_id AS cat','goal.total_seconds', 'category.name','sub_category.name AS sub_cat_name')
                 ->join('goal','task.cat_id','=','goal.cat_id')
                 ->join('category','goal.cat_id','=','category.id')
                 ->join('sub_category','task.sub_cat_id','=','sub_category.id')
                 ->groupBy('cat')
                 ->where('task.user_id', $user->id)
                 ->get();

                 foreach ($goals as $value) {

                    $value; 
                  }


                  $category = DB::table('category')->select('id','name')
                  //->groupBy('cat_id')
                  ->where('id', $value->cat)
                  ->get();
                 




                  $json = $goals.$category;

                  return response()->json(['data' => $json,'status' => 1,'message' => "Success"]);

                   

















                   /*$goal = Goal::select('cat_id','total_seconds')
                    ->where('user_id', $user->id)
                    ->groupBy('cat_id')
                    ->get();

                    $task = Task::select('cat_id','sub_cat_id','total_seconds')
                    ->groupBy('cat_id')
                    ->get();
                    
                     foreach($task as $tasks){

                        $tasks['total_seconds'];

                        foreach($goal as $goals){

                            if($tasks['cat_id'] == $goals['cat_id'])

                            {
                                  
                                  $tasks['total_seconds'];

 
                            }

                        }


                    } 
 
 
return response()->json(['data' => $task ,'status' => 1,'message' => "Success"]); 
   
*/
         


                  




 





 /* foreach ($goals as $value) {

              $value;
              

  } 
      */      //print_r($value);exit;

            /*if($value){

              DB::table('task')
        ->where('task.cat_id', '=', $request->cat_id)
        ->where('task.sub_cat_id' , '=', $request->sub_cat_id)
        ->where('user_id', $user->id)
        ->update(['task.total_seconds'=> $request->total_seconds]);
        return response()->json(['status'=>1,'message'=>"Success"]);




            }*/
            //print_r($test);exit;

/*$taks = task::select('sub_cat_id','total_seconds')
                    ->where('user_id', $user->id)->get();

                    $goals = Goal::select('cat_id','total_seconds')->get();
                    
                     foreach($taks as $totaltask){

                        $totaltask['total']=0;

                        foreach($goals as $goal){

                            if($totaltask['total_seconds'] == $goal['cat_id'])

                            {
                                 $totaltask['total']+=1;
                                 //print_r($cat['sub_cat_count']);exit;
                            }

                        }


                    } 

                     
         return response()->json(['data' => $goals,'status' => 1,'message' => "Success"]);
*/








 
//return response()->json(['data' => $goals,'status' => 1,'message' => "Success"]);
   


        

/*
        $goals = DB::table('goal')
->select('goal.cat_id','goal.total_seconds','task.cat_id','task.sub_cat_id')
->join('task','goal.user_id','=','task.user_id')
//->join('sub_category','sub_category.user_id','=','goal.sub_cat_id')
//->join('sub_category','sub_category.id','=','goal.sub_cat_id')
->where('task.user_id', $user->id)->get();

    //print_r($goals);exit;

return response()->json(['data' => $goals,'status' => 1,'message' => "Success"]); */










      }



      public function listoftime(Request $request){
       try{
       $header = $request->header('Authorization');
       if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 0,'message' => "User not found"]);
       }else{

                  $category = Category::all(); 

                  $goals = Goal::select('goal.*','category.name','category.slug','category.icon')
                   ->leftjoin('category','goal.cat_id','=','category.id')
                   ->where('goal.user_id', $user->id)
                   ->get();
                   
                  $task = Task::select('task.*','goal.cat_id','sub_category.id','sub_category.name AS subcat_name')
                   ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
                   ->leftjoin('goal','task.cat_id','=','goal.cat_id')
                   ->where('task.user_id', $user->id)
                   ->get();
                 $data = [];
        
                 foreach($category as $key => $value){
                     $data[$value->name] = $value;
                       foreach($goals as $key => $val){
                         if(isset($val->name)){
                           $data[$val->name] = $val->toArray();

                          foreach($task as $key => $tasks){
                            if($tasks->cat_id == $val['cat_id']){
                           
                             if(isset($data[$val->name]['Subcategory']) && !empty($data[$val->name]['Subcategory'])){
                         
                                $data[$val->name]['Subcategory'][] = $tasks->toArray();
                             }else{ 
                             $data[$val->name]['Subcategory'] = [];
                             $data[$val->name]['Subcategory'][] = $tasks->toArray(); 
                          
                             } 
                          } 
                        } 
                      }
                   }
                 }
                 foreach($data as $key => $catdata){
                            $cats[]=$catdata;
                         }
                 return response()->json(['data' => $cats,'status' => 1,'message' => "Success"]);
                 }
                 }
         catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }
 
      }






}