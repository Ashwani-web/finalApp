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
use Carbon\Carbon;
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


      public function listofgoal(Request $request){
        
         try{
       $header = $request->header('Authorization');
       if(!$user = JWTAuth::parseToken()->authenticate()){

        return response()->json(['status' => 0,'message' => "User not found"]);
       }
        // if($request->type == 'D'){
        // $goallist = DB::table("goal")->select('total_seconds')->where("user_id", $user->id)->get();
        // $status = 1;
        // $message = "Success";
        // return response()->json(compact('goallist','status','message'));
        //  }else{
        // $goallist = DB::table("goal")->select('total_seconds')->where("user_id", $user->id)->get();
        // $status = 1;
        // $message = "Success";
        // return response()->json(compact('goallist','status','message'));
        //  }
       if($request->type == 'D'){
       /*$goals = Goal::select('goal.cat_id','goal.total_seconds','goal.user_id','category.id','category.name','task.cat_id','task.user_id')
         ->join('category','goal.cat_id','=','category.id')
         ->join('task','goal.cat_id','=','task.cat_id')
          ->where('goal.user_id', $user->id)->get();*/

     /*     $goals = Goal::select ('goal.cat_id','goal.total_seconds','goal.user_id','category.id','category.name','task.cat_id','task.sub_cat_id','task.total_seconds as time','sub_category.fk_cat_id','sub_category.name')
            ->JOIN('category','goal.cat_id','=','category.id')
            ->JOIN('task','goal.cat_id','=','task.cat_id')
            ->JOIN('sub_category','goal.cat_id','=','sub_category.fk_cat_id')
            ->where('goal.user_id', $user->id)->get();
*/
//print_r($goals);exit;
            /*$goals = Goal::select ('cat_id','total_seconds','user_id')->where('user_id', $user->id)->get();
              
            $category = Category::select('category.id','category.name')->get();
            $task = Task::select ('task.cat_id','task.sub_cat_id','task.total_seconds')->where("user_id", $user->id)->get();*/



            /*$goals = Goal::select('goal.cat_id','goal.total_seconds','goal.user_id','category.id','category.name','task.cat_id','task.user_id','task.total_seconds')
         ->join('category','goal.cat_id','=','category.id')
         ->join('task','goal.cat_id','=','task.cat_id')
          ->where('goal.user_id', $user->id)->get();
            $subcategory = Subcategory::select ('sub_category.fk_cat_id','sub_category.name')->where("user_id", $user->id)->get();*/


            $goals = Goal::select('goal.cat_id','goal.total_seconds','goal.user_id','category.id','category.name')
         ->join('category','goal.cat_id','=','category.id')
         ->where('goal.user_id', $user->id)->get();
         $task = Task::select('task.cat_id','task.sub_cat_id','task.user_id','task.total_seconds','sub_category.fk_cat_id','sub_category.name as subcat_name')
         ->join('sub_category','task.sub_cat_id','=','sub_category.id')
          ->where('task.user_id', $user->id)->get();
            //$subcategory = Subcategory::select ('sub_category.fk_cat_id','sub_category.name')->where("user_id", $user->id)->get();



 
            //print_r($goals);exit;
               $data = [];
              /*foreach($goals as $key => $value){

                 $data[$value->cat_id] = $value;
                 foreach ($task as $key => $val) {

                     $tasks=$val->toArray();
                     
                     //$data[$value->cat_id]['subcategory'] = $tasks;


                    
                    }
              }*/

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

              foreach ($data as $key => $catdatas) {
           
                $cat[]=$catdatas;

              }

            

            /*$data = [];
        
                 foreach($goals as $key => $value){

                    // $value->cat_id = $value->id;
                     //$data[$value->name] = $value;

                    //$data[$value->cat_id] = $value->toArray();

                  $data[$value->cat_id] = $value;
                      
                    foreach ($category as $key => $cat) {
                      //$data[$cat->id]['subcategory'] = $cat;
                      $cate = $cat->toArray();
                     
                      //$data[$cate['id']]['subcategory'][] = $subcategory;
                       
                      //$data[$cate['id']]['subcategory'] = $subcategory;
                      
                  foreach ($subcategory as $key => $val) {

                     $subcat=$val->toArray();
                     if($value->cat_id === $val['fk_cat_id'])
                     $data[$cate['id']]['subcategory'] = $task;
                    
                    }

 
                    }


                 }


              foreach ($data as $key => $catdatas) {
           
                $cat=$catdatas;

              }*/
             

       //$category = Category::select('id','name')->get();
                 
                

    return response()->json(['data' => $cat,'status' => 11,'message' => "Success"]); 
      



                    // foreach($category as $cat){
                    //    $cat['total_seconds']=0;
                    //       foreach($goals as $goal){
                    //         if($cat['id'] == $goal['cat_id'])
                    //         {
                    //              $cat['total_seconds']+=$goal->total_seconds;
                                
                    //         }

                    //     }
                    //  } 

                   }
                   else{
                    /*$goals = Goal::select('cat_id','total_seconds','user_id')
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
                     }*/ 

                     $goals = Goal::select('goal.cat_id','goal.total_seconds','goal.user_id','category.id','category.name')
         ->join('category','goal.cat_id','=','category.id')
         ->where('goal.user_id', $user->id)->get();
         $task = Task::select('task.cat_id','task.sub_cat_id','task.user_id','task.total_seconds','sub_category.fk_cat_id','sub_category.name as subcat_name')
         ->join('sub_category','task.sub_cat_id','=','sub_category.id')
          ->where('task.user_id', $user->id)->get();
          $data = [];

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

              foreach ($data as $key => $catdatas) {

                $catdatas['total_seconds']*=7;
                $cat[]=$catdatas;

              }

return response()->json(['data' => $cat,'status' => 1,'message' => "Success"]); 
 
                   }

                     
         // return response()->json(['total_seconds' => $totaltime ,'data' => $category,'status' => 1,'message' => "Success"]); 


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
       
       $subcategory = Subcategory::select('id','name')->where('name','LIKE','%'.$request->q."%")->where('flag', '=' ,0)->get();
      
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
            
          $status = 1;
          $message = "Success";
          return response()->json(compact('subcategory','status','message'));
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
          //if(empty($request->sub_cat_id)){
         if($request->sub_cat_id == 0){
            $subcategory = new Subcategory;
            $subcategory->user_id = $user->id;
            $subcategory->fk_cat_id = $request->cat_id;
            $subcategory->name = $request->name;
            $subcategory->icon = 'test';
            $url = strtolower($request->name);
            $subcategory->slug = str_replace(' ', '-', $url);
            $subcategory->save();
            
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
            'cat_id' => 'required|alpha_dash|',
            'start_time' => 'required|integer',
            'end_time' => 'required|integer',
            'total_seconds' => 'required',
            
            ]);
            if($validator->fails()){
              $status=0;
              $message = implode(" and ",$validator->errors()->all());
              return response()->json(compact('status','message'));

             }

             
 
              $mytime = Carbon::now();
              $dates= $mytime->toDateString();
              $goalstate = Goalstatus::select('total_seconds','created_at','user_id')
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', $dates)
                    ->get();
              $totaltime = $goalstate->sum('total_seconds'); 
             
             //print_r($totaltime);exit;

             if($totaltime>=86400){
             return response()->json(['status'=>0,'message'=>"You have already used 24 hour"]);
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
      }else{
        $goalstatus->allocated_st = $task->total_seconds;
      }

      
      $goalstatus->note=$request->note;
      $goalstatus->save();
      return response()->json(['status'=>1,'message'=>"Success"]);
    }
    else{

      $goal = DB::table("goal")->where("user_id", $user->id)
        ->where("cat_id", $request->cat_id)->first();
      $goalstatus->cat_id = $request->cat_id;
      
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
 
        else{

    if (empty($request->start_time)  && empty($request->end_time)){
    
    $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                 ->where('goal_status.user_id', $user->id)
                 ->get();
   
    }
    elseif (!empty($request->start_time)  && empty($request->end_time)){
    
    $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
                 ->join('category','goal_status.cat_id','=','category.id')
                 ->join('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                
                 ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=' , $request->start_time)
                 ->get();
      
    
    }
 else{
    $goalstatus = Goalstatus::select('goal_status.cat_id','goal_status.note','goal_status.id as goal_status_id','goal_status.sub_cat_id','goal_status.user_id','goal_status.start_time','goal_status.end_time','goal_status.total_seconds','goal_status.allocated_st',
        'category.name','category.slug','sub_category.name AS subcat_name')
               
    
    ->leftJoin('sub_category', function($join) {
        $join->on('goal_status.sub_cat_id', '=', 'sub_category.id');
      })
    ->join('category','goal_status.cat_id','=','category.id')
    ->where('goal_status.user_id', $user->id)
    ->where('goal_status.start_time', '>=' , $request->start_time)
    ->where('goal_status.end_time','<=', $request->end_time)->get();

  }
foreach ($goalstatus as $key => &$value) {
          $value->start_time = intval($value->start_time);
          $value->end_time = intval($value->end_time);

          $value->cat_id = $value->cat_id;
          //$value->note = $value->note;
          $value->note = isset($value['note'])?$value['note']:'';
          $value->goal_status_id = $value->goal_status_id;
          $value->sub_cat_id = $value->sub_cat_id;
          $value->user_id = $value->user_id;
          $value->total_seconds = $value->total_seconds;
          $value->allocated_st = $value->allocated_st;
          $value->name = $value->name;
          $value->slug = $value->slug;
          //$value->subcat_name = $value->subcat_name;
          $value->subcat_name = isset($value['subcat_name'])?$value['subcat_name']:'';



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

        //$subjectivegoal = DB::table("subjective_goal")->where("user_id", $user->id)->get();
        $subjectivegoal = DB::table("subjective_goal as SG")->select('SG.*',DB::raw("'false' as status"))->where("user_id", $user->id)->get();

        $status = 1;
        $message = "Success";
        return response()->json(compact('subjectivegoal','status','message'));



       }

     catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }




      }


      public function listoftime(Request $request){
       try{
       $header = $request->header('Authorization');
       if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 0,'message' => "User not found"]);
       }else{


                if(empty($request->cat_id)){
                  $category = Category::all(); 

                  $goals = Goal::select('goal.*','category.name','category.slug','category.icon')
                   ->leftjoin('category','goal.cat_id','=','category.id')
                   ->where('goal.user_id', $user->id)
                   ->get();
                   
                 /* $task = Task::select('task.*','goal.cat_id','sub_category.id','sub_category.name AS subcat_name')
                   ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
                   ->leftjoin('goal','task.cat_id','=','goal.cat_id')
                   ->where('task.user_id', $user->id)
                   ->get();*/

                   $task = Task::select('task.*','sub_category.id','sub_category.name AS subcat_name')
                   ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
                   //->leftjoin('goal','task.cat_id','=','goal.cat_id')
                   ->where('task.user_id', $user->id)
                   ->get();
                 $data = [];
        
                 foreach($category as $key => $value){
                  $value->cat_id = $value->id;
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
                 

                         
                 foreach($data as $key => $catdatas){
                 
                  unset($catdatas['id']);
                  $catdatas['user_id']= isset($catdatas['user_id'])?$catdatas['user_id']:$user->id;
                  $catdatas['cat_id'] =isset($catdatas['cat_id'])?$catdatas['cat_id']:0;
                   $catdatas['Subcategory'] = isset($catdatas['Subcategory'])?$catdatas['Subcategory']:[];
                  $catdatas['total_seconds'] =isset($catdatas['total_seconds'])?$catdatas['total_seconds']:0;
                  $catdatas['is_active'] = isset($catdatas['is_active'])?$catdatas['is_active']:1;
                  $catdatas['name'];


        
                            $cat[]=$catdatas;
                         }
                



                 return response()->json(['data' => $cat,'status' => 11,'message' => "Success"]);
                 }

                 else{



                  $category = Category::all(); 
                   $goals = Goal::select('goal.*','category.name','category.slug','category.icon')
                   ->leftjoin('category','goal.cat_id','=','category.id')
                   ->where('goal.user_id', $user->id)
                   ->where('goal.cat_id', $request->cat_id)
                   ->get();
                    $task = Task::select('task.*','sub_category.id','sub_category.name AS subcat_name')
                   ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
                   //->leftjoin('goal','task.cat_id','=','goal.cat_id')
                   ->where('task.user_id', $user->id)
                   ->where('task.cat_id', $request->cat_id)
                   ->get();
                 $data = [];
        
                 /*foreach($category as $key => $value){
                  $value->cat_id = $value->id;
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
                 }*/
                 
        /*
                 foreach($data as $key => $catdatas){
                 
                  unset($catdatas['id']);
                  $catdatas['user_id']= isset($catdatas['user_id'])?$catdatas['user_id']:$user->id;
                  $catdatas['cat_id'] =isset($catdatas['cat_id'])?$catdatas['cat_id']:0;
                   $catdatas['Subcategory'] = isset($catdatas['Subcategory'])?$catdatas['Subcategory']:[];
                  $catdatas['total_seconds'] =isset($catdatas['total_seconds'])?$catdatas['total_seconds']:0;
                  $catdatas['is_active'] = isset($catdatas['is_active'])?$catdatas['is_active']:1;
                  $catdatas['name'];
                   $cat[]=$catdatas;
                         }*/


                         foreach($goals as $key => $val){

                          $data[$val->name] = $val->toArray();
                          foreach($task as $key => $tasks){
                            if($tasks->cat_id == $val['cat_id']){

                               $data[$val->name]['Subcategory'][] = $tasks->toArray();
                            }
                          }
                         }
                
                   return response()->json(['data' => $data,'status' => 1,'message' => "Success"]);


                 }




   


                 }









                 }
         catch (JWTException $e) {
         return response()->json(['status' => 0,'message' => "Token expired"]);
        }
 
      }

      public function taskdelete(Request $request){

        try{
       $header = $request->header('Authorization');
       if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 0,'message' => "User not found"]);
       }
       DB::table("task")->where("user_id", $user->id)
       ->where("cat_id", $request->cat_id)
       ->where("sub_cat_id", $request->sub_cat_id)->delete();
      
       return response()->json(['status' => 1,'message' => " Delete Task Successfully"]);
   }
    catch (JWTException $e) {
    return response()->json(['status' => 0,'message' => "Token expired"]);
     }  


      }



      public function summary(Request $request){

       $header = $request->header('Authorization');
       $user = JWTAuth::parseToken()->authenticate();
               //$category = Category::all(); 

               $goalstatus = Goalstatus::select('goal_status.*','category.name')
               ->leftjoin('category','goal_status.cat_id','=','category.id')
               ->where('goal_status.user_id', $user->id)
               ->get();
               $totaltime = $goalstatus->sum('total_seconds'); 
                $time=($totaltime*100)/24;


                $task = Task::select('task.*','sub_category.name','sub_category.id')
               ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
               ->where('task.user_id', $user->id)
               ->get();
                $data = [];
                foreach ($goalstatus as $key => $value) {
                if(isset($value->name)){
                           //$data[$value->name] = $value->toArray();
                            foreach($task as $key => $tasks){
                            //if($value->cat_id == $tasks['cat_id']){
                            if([$value->cat_id] == [$tasks->cat_id]){
   $data['target'][][$value->name][]['Subcategory'][] = $tasks->toArray();
                          }
                         }
                        }
                    }
 return response()->json(['data' => $data,'time' => $time,'status' => 1,'message' => "Success"]);


     /*  $header = $request->header('Authorization');
       $user = JWTAuth::parseToken()->authenticate();

       //$category = Category::all(); 

              $category = Category::select('category.name','category.id')->get();

               $goalstatus = Goalstatus::select('goal_status.*')
               ->where('goal_status.user_id', $user->id)
               ->get(); 

            $task = Task::select('task.*','sub_category.name','sub_category.id')
            ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
               ->where('task.user_id', $user->id)
               ->get();

               //$subcategory = Subcategory::select('sub_category.name','sub_category.id')->where('sub_category.user_id', $user->id)->get();  
                
                $data = [];
                foreach ($category as $key => $value) {
                if(isset($value->id)){
                           //$data['target'][][$value->name] = $value->toArray();

                     foreach($goalstatus as $key => $goalstate){
                      if($value->id == $goalstate['cat_id']){
                         $data['target'][][$value->name]= $goalstate->toArray();
                        
                }
             }
          } 
      }
                
           

 return response()->json(['data' => $data,'status' => 1,'message' => "Success"]);

*/





 }



 public function summary1(Request $request){

      /* $header = $request->header('Authorization');
       $user = JWTAuth::parseToken()->authenticate();*/
 try{
       $header = $request->header('Authorization');
       if (! $user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['status' => 0,'message' => "User not found"]);
       }
 
            if($request->type == 'D'){
       //if (!empty($request->start_time)  && empty($request->end_time)){
                   //$category = Category::all(); 
                   $goals = Goal::select('goal.user_id','goal.cat_id','category.name','category.slug','category.icon',DB::raw('sum(goal.total_seconds) as goaltime'))
                   ->leftjoin('category','goal.cat_id','=','category.id')
                   ->where('goal.user_id', $user->id)
                   ->groupBy('goal.cat_id')
                   ->get();
                    
                    $goalstatus = Goalstatus::select('goal_status.*','sub_category.name','sub_category.slug','sub_category.icon')
                   ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                   ->leftjoin('task','goal_status.cat_id','=','task.cat_id')
                   ->where('goal_status.user_id', $user->id)
                 //->where('goal_status.start_time', '>=' , $request->start_time)
                 //->where('goal_status.end_time','<=', $request->end_time)
                   
                   ->groupBy('goal_status.sub_cat_id')
                    ->get();


 

                    $data = [];
                     foreach($goals as $key => $val){
                         $data[$val->name] = $val->toArray();
                          $times = DB::table('goal_status')
                          ->where('user_id',$user->id)
                          ->where('cat_id',$val['cat_id'])
                          ->sum('total_seconds');
                          $data[$val->name]['actualtime'] = $times;
                            foreach($goalstatus as $key => $goalstate){
                             if($goalstate->cat_id == $val['cat_id']){

      $goalstate->start_time = intval($goalstate->start_time);
      $goalstate->end_time = intval($goalstate->end_time);
      $goalstate->allocated_st = intval($goalstate->allocated_st);
      $goalstate->note = isset($goalstate['note'])?$goalstate['note']:'';
      $goalstate->slug = isset($goalstate['slug'])?$goalstate['slug']:'';
      $goalstate->icon = isset($goalstate['icon'])?$goalstate['icon']:'';
      $goalstate->name = isset($goalstate['name'])?$goalstate['name']:'';

      $data[$val->name]['Subcategory'][] = $goalstate->toArray();
                           
                            }

                            }
                          
                          }
                        foreach ($data as $key => $catdatas) {
                           // $catdatas['total_seconds'] =$times;
                           //$catdatas['total'] =$times;

          $catdatas['goaltime'] = intval($catdatas['goaltime']);
          $catdatas['goaltime'] = intval($catdatas['goaltime']);
            //$catdatas['goaltime'] = intval($catdatas['goaltime']/3600);
            // $catdatas['actualtime'] = intval($catdatas['actualtime']/3600);
                $catdatas['Subcategory'] = isset($catdatas['Subcategory'])?$catdatas['Subcategory']:[];

                   $cat[]=$catdatas;
                          }
                
                 return response()->json(['data' => $cat,'status' => 11,'message' => "Success"]);

               }


               if($request->type == 'W'){

 
                    $goals = Goal::select('goal.user_id','goal.cat_id','category.name','category.slug','category.icon',DB::raw('sum(goal.total_seconds) as goaltime'))
                   ->leftjoin('category','goal.cat_id','=','category.id')
                   ->where('goal.user_id', $user->id)
                   ->groupBy('goal.cat_id')
                   ->get();
                    
                    $goalstatus = Goalstatus::select('goal_status.*','sub_category.name','sub_category.slug','sub_category.icon')
                   ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                   ->leftjoin('task','goal_status.cat_id','=','task.cat_id')
                   ->where('goal_status.user_id', $user->id)
                   ->where('goal_status.start_time', '>=' , $request->start_time)
                   ->where('goal_status.end_time','<=', $request->end_time)
                   ->groupBy('goal_status.sub_cat_id')
                   ->get();

                     $data = [];
                     foreach($goals as $key => $val){
                          $data[$val->name] = $val->toArray();
                          $times = DB::table('goal_status')
                          ->where('user_id',$user->id)
                          ->where('cat_id',$val['cat_id'])
                          ->sum('total_seconds');
                           $data[$val->name]['actualtime'] = $times;
                        foreach($goalstatus as $key => $goalstate){
                             if($goalstate->cat_id == $val['cat_id']){

      $goalstate->start_time = intval($goalstate->start_time);
      $goalstate->end_time = intval($goalstate->end_time);
      $goalstate->allocated_st = intval($goalstate->allocated_st);
      $goalstate->note = isset($goalstate['note'])?$goalstate['note']:'';
      $goalstate->slug = isset($goalstate['slug'])?$goalstate['slug']:'';
      $goalstate->icon = isset($goalstate['icon'])?$goalstate['icon']:'';
      $goalstate->name = isset($goalstate['name'])?$goalstate['name']:'';

      $data[$val->name]['Subcategory'][] = $goalstate->toArray();
                           
                              }
                            }
                          
                          }
                        foreach ($data as $key => $catdatas) {
                   $catdatas['goaltime'] = intval($catdatas['goaltime']);
                   $catdatas['actualtime'] = intval($catdatas['actualtime']);
                   $catdatas['Subcategory'] = isset($catdatas['Subcategory'])?$catdatas['Subcategory']:[];

                   $cat[]=$catdatas;
                          }
                
                 return response()->json(['data' => $cat,'status' => 1,'message' => "Success"]);

               }



}


catch (JWTException $e) {
    return response()->json(['status' => 0,'message' => "Token expired"]);
     }  
              


     }


public function deletecreateTime(Request $request){


    try{
     $header = $request->header('Authorization');
     if (! $user = JWTAuth::parseToken()->authenticate()) {
      return response()->json(['status' => 0,'message' => "User not found"]);
     }
     $goalstatus = Goalstatus::find($request->id)->where('user_id',$user->id)->where('id',$request->id)->delete();

     return response()->json(['status' => 1,'message' => " Success"]);
     }
  catch (JWTException $e) {
  return response()->json(['status' => 0,'message' => "Token expired"]);
   } 
  }


}