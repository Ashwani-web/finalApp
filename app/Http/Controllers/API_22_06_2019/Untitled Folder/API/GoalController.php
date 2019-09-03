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

class GoalController extends Controller {

    public function creategoal(Request $request) {
        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            //

            $goalexist = Goal::select('user_id', 'cat_id')
                    ->where('user_id', '=', ($user->id))
                    ->where('cat_id', '=', ($request->cat_id))
                    ->first();
            //print_r($goalexist);exit;

            if (!empty($goalexist)) {

                DB::table('goal')
                        ->where('user_id', '=', ($user->id))
                        ->where('cat_id', '=', ($request->cat_id))
                        ->update(['goal.total_seconds' => $request->total_seconds]);
                return response()->json(['status' => 1, 'message' => "Success"]);
            } else {
                $goal = new Goal;
                $goal->user_id = $user->id;
                $goal->cat_id = $request->cat_id;
                $goal->total_seconds = $request->total_seconds;
                $goal->save();

                return response()->json(['status' => 1, 'message' => "Success"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function listofgoal(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            if ($request->type == 'D') {

                $goals = Goal::select('goal.cat_id', 'goal.total_seconds', 'goal.user_id', 'category.id', 'category.name')
                                ->join('category', 'goal.cat_id', '=', 'category.id')
                                ->where('goal.user_id', $user->id)->get();
                $task = Task::select('task.cat_id', 'task.sub_cat_id', 'task.user_id', 'task.total_seconds', 'sub_category.fk_cat_id', 'sub_category.name as subcat_name')
                                ->join('sub_category', 'task.sub_cat_id', '=', 'sub_category.id')
                                ->where('task.user_id', $user->id)->get();
                $data = [];

                foreach ($goals as $key => $val) {
                    if (isset($val->name)) {
                        $data[$val->name] = $val->toArray();

                        foreach ($task as $key => $tasks) {
                            if ($tasks->cat_id == $val['cat_id']) {

                                if (isset($data[$val->name]['Subcategory']) && !empty($data[$val->name]['Subcategory'])) {

                                    $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                } else {
                                    $data[$val->name]['Subcategory'] = [];
                                    $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                }
                            }
                        }
                    }
                }

                foreach ($data as $key => $catdatas) {

                    $cat[] = $catdatas;
                }

                return response()->json(['data' => $cat, 'status' => 1, 'message' => "Success"]);
            } else {

                $goals = Goal::select('goal.cat_id', 'goal.total_seconds', 'goal.user_id', 'category.id', 'category.name')
                                ->join('category', 'goal.cat_id', '=', 'category.id')
                                ->where('goal.user_id', $user->id)->get();
                $task = Task::select('task.cat_id', 'task.sub_cat_id', 'task.user_id', 'task.total_seconds', 'sub_category.fk_cat_id', 'sub_category.name as subcat_name')
                                ->join('sub_category', 'task.sub_cat_id', '=', 'sub_category.id')
                                ->where('task.user_id', $user->id)->get();
                $data = [];

                foreach ($goals as $key => $val) {
                    if (isset($val->name)) {
                        $data[$val->name] = $val->toArray();

                        foreach ($task as $key => $tasks) {
                            if ($tasks->cat_id == $val['cat_id']) {

                                if (isset($data[$val->name]['Subcategory']) && !empty($data[$val->name]['Subcategory'])) {

                                    $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                } else {
                                    $data[$val->name]['Subcategory'] = [];
                                    $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                }
                            }
                        }
                    }
                }

                foreach ($data as $key => $catdatas) {

                    $catdatas['total_seconds']*=7;
                    $cat[] = $catdatas;
                }

                return response()->json(['data' => $cat, 'status' => 1, 'message' => "Success"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function searchsubcategory(Request $request) {


        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            $subcategory = Subcategory::select('id', 'name')->where('name', 'LIKE', '%' . $request->q . "%")->where('flag', '=', 0)->where('user_id', '=', $user->id)->get();
            ;

            //$subcategory=DB::table('sub_category')->where('name','LIKE','%'.$request->q."%")->get();

            if ($subcategory) {
                foreach ($subcategory as $value) {

                    $value->name;
                }
                /* $status = 1;
                  $message = "Success";
                  return response()->json(compact('subcategory','status','message')); */
                if (!empty($value->name)) {
                    $status = 1;
                    $message = "Success";
                    return response()->json(compact('subcategory', 'status', 'message'));
                } else {
                    // return response()->json(['status' => 0,'message' => "Subcategory not exist"]);
                    $status = 1;
                    $message = "Success";
                    return response()->json(compact('subcategory', 'status', 'message'));
                }
            } else {

                return response()->json(['status' => 0, 'message' => "Subcategory not exist"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function createtask(Request $request) {

        $validator = Validator::make($request->all(), [
                    'cat_id' => 'required|alpha_dash',
                    'total_seconds' => 'required|alpha_dash',
        ]);
        if ($validator->fails()) {
            $status = 0;
            $message = implode(" and ", $validator->errors()->all());
            return response()->json(compact('status', 'message'));
        }

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            }
            $tsk = DB::table('task')
                    ->where('user_id', $user->id)
                    ->where('cat_id', $request->cat_id)
                    ->where('sub_cat_id', $request->sub_cat_id)
                    ->get();
            foreach ($tsk as $value) {
                $value;
            }

            if (!empty($value)) {

                DB::table('task')
                        ->where('task.cat_id', '=', $request->cat_id)
                        ->where('task.sub_cat_id', '=', $request->sub_cat_id)
                        ->where('user_id', $user->id)
                        ->update(['task.total_seconds' => $request->total_seconds]);
                return response()->json(['status' => 1, 'message' => "Success"]);
            } else {
                //if(empty($request->sub_cat_id)){
                if ($request->sub_cat_id == 0) {
                    $subcategory = new Subcategory;
                    $subcategory->user_id = $user->id;
                    $subcategory->fk_cat_id = $request->cat_id;
                    $subcategory->name = $request->name;
                    $subcategory->icon = 'test';
                    $url = strtolower($request->name);
                    $subcategory->slug = str_replace(' ', '-', $url);
                    $subcategory->save();
                    // return response()->json(['status' => 1,'message' => "Subcategory created Successfully"]);
                    $subcategorys = DB::table('sub_category')->where("user_id", $user->id)->latest()->first();
                    $task = new Task;
                    $task->cat_id = $subcategorys->fk_cat_id;
                    $task->sub_cat_id = $subcategorys->id;
                    $task->user_id = $user->id;
                    $task->total_seconds = $request->total_seconds;
                    $task->save();

                    $status = 1;
                    $message = "Success";
                    return response()->json(compact('task', 'status', 'message'));
                } else {

                    $task = new Task;
                    $task->cat_id = $request->cat_id;
                    $task->sub_cat_id = $request->sub_cat_id;
                    $task->user_id = $user->id;
                    $task->total_seconds = $request->total_seconds;
                    $task->save();
                    return response()->json(['status' => 1, 'message' => "Success"]);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }
    
    public function editsubcategory(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        
            ]);
            if ($validator->fails()) {
                $status = 0;
                $message = implode(" and ", $validator->errors()->all());
                return response()->json(compact('status', 'message'));
            }
            if(isset($request->id) && !empty($request->id)){
                $subcategory = subcategory::select('*')
                    ->where('id', $request->id)
                    ->where('user_id',$user->id)
                    ->first();
           

                if (isset($subcategory) && !empty($subcategory)) {
                    if (isset($request->name) && !empty($request->name)) {
                       $subcategory->name = $request->name;
                    }                   
                    $subcategory->save();
                    $status = 1;
                    $message = "Success";
                    return response()->json(['status' => 1, 'message' => "Updated successfully"]);
                }else{
                    return response()->json(['status' => 1, 'message' => "subcategory not found"]);
                }
            }            
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function createtime(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            $validator = Validator::make($request->all(), [
                        'cat_id' => 'required|alpha_dash',
                        'start_time' => 'required|alpha_dash|min:10|max:10',
                        'end_time' => 'required|alpha_dash|min:10|max:10',
                        'total_seconds' => 'required|alpha_dash',
            ]);
            if ($validator->fails()) {
                $status = 0;
                $message = implode(" and ", $validator->errors()->all());
                return response()->json(compact('status', 'message'));
            }

            /* $goalstate = Goalstatus::select('total_seconds','user_id')
              ->where('user_id', $user->id)->get();
              $totaltime = $goalstate->sum('total_seconds');
              if($totaltime>86400){
              return response()->json(['status'=>0,'message'=>"You have already used 24 hour"]);
              }
             */


            $goalstatus = new Goalstatus;
        if (!empty($request->cat_id)){
            if (!empty($request->sub_cat_id)) {

                $task = DB::table("task")->where("user_id", $user->id)
                                ->where("cat_id", $request->cat_id)
                                ->where("sub_cat_id", $request->sub_cat_id)->first();

                $goalstatus->cat_id = $request->cat_id;
                $goalstatus->sub_cat_id = $request->sub_cat_id;
                $goalstatus->start_time = $request->start_time;
                $goalstatus->user_id = $user->id;
                $goalstatus->end_time = $request->end_time;
                $goalstatus->total_seconds = $request->total_seconds;

                if (empty($task->total_seconds)) {
                    $goalstatus->allocated_st = 0;
                } else {
                    $goalstatus->allocated_st = $task->total_seconds;
                }

                // $goalstatus->allocated_st=$task->total_seconds;
                $goalstatus->note = $request->note;
                $goalstatus->save();
                return response()->json(['status' => 1, 'message' => "Success"]);
            }
            else {

                $goal = DB::table("goal")->where("user_id", $user->id)
                                ->where("cat_id", $request->cat_id)->first();
                $goalstatus->cat_id = $request->cat_id;
                //$goalstatus->sub_cat_id=$request->sub_cat_id;
                $goalstatus->sub_cat_id = 0;
                $goalstatus->start_time = $request->start_time;
                $goalstatus->user_id = $user->id;
                $goalstatus->end_time = $request->end_time;
                $goalstatus->total_seconds = $request->total_seconds;
                if (empty($goal->total_seconds)) {
                    $goalstatus->allocated_st = 0;
                } else {
                    $goalstatus->allocated_st = $goal->total_seconds;
                }
                $goalstatus->note = $request->note;
                $goalstatus->save();
                return response()->json(['status' => 1, 'message' => "Success"]);
        }
            }else{
                return response()->json(['status' => 0, 'message' => "Please Select a Category"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function updatecreatetime(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }
            //$goalstatus = DB::table("goal_status")->where('id', $request->id)->where("user_id", $user->id)->first();
            //$goalstatus = Goalstatus::find()->where('id',$request->id)->where('user_id',$user->id)->first();
            $goalstatus = Goalstatus::select('*')
                ->where('id', $request->id)
                ->where('user_id',$user->id)
                ->first();
           

            if ($goalstatus) {
                if (isset($request->cat_id) && !empty($request->cat_id)) {
                    $goalstatus->cat_id = $request->cat_id;
                }
                if (isset($request->sub_cat_id) && !empty($request->sub_cat_id)) {
                    $goalstatus->sub_cat_id = $request->sub_cat_id;
                }
                if (isset($request->start_time) && !empty($request->start_time)) {
                    $goalstatus->start_time = $request->start_time;
                }
                if (isset($request->end_time) && !empty($request->end_time)) {
                    $goalstatus->end_time = $request->end_time;
                }
                if (isset($request->total_seconds) && !empty($request->total_seconds)) {
                    $goalstatus->total_seconds = $request->total_seconds;
                }
                if (isset($request->allocated_st) && !empty($request->allocated_st)) {
                    $goalstatus->allocated_st = $request->allocated_st;
                }
                if (isset($request->note) && !empty($request->note)) {
                    $goalstatus->note = $request->note;
                }

                $goalstatus->save();
                $status = 1;
                $message = "Success";

                return response()->json(compact('goalstatus', 'status', 'message'));
            } else {

                return response()->json(['status' => 0, 'message' => "Error"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function timestatus(Request $request) {
        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            } else {

                if (empty($request->start_time) && empty($request->end_time)) {

                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
                            ->leftjoin('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
                            ->where('goal_status.user_id', $user->id)
                            ->get();
                } elseif (!empty($request->start_time) && empty($request->end_time)) {

                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
                            ->join('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
                            //->where('goal_status.user_id', $user->id)
                            ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=', $request->start_time)
                            ->get();
                } else {
                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                                    // ->leftJoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                                    ->leftJoin('sub_category', function($join) {
                                        $join->on('goal_status.sub_cat_id', '=', 'sub_category.id');
                                    })
                                    ->join('category', 'goal_status.cat_id', '=', 'category.id')
                                    ->where('goal_status.user_id', $user->id)
                                    ->where('goal_status.start_time', '>=', $request->start_time)
                                    ->where('goal_status.end_time', '<=', $request->end_time)->get();
                }
                foreach ($goalstatus as $key => &$value) {
                    $value->start_time = intval($value->start_time);
                    $value->end_time = intval($value->end_time);

                    $value->cat_id = $value->cat_id;
                    //$value->note = $value->note;
                    $value->note = isset($value['note']) ? $value['note'] : '';
                    $value->goal_status_id = $value->goal_status_id;
                    $value->sub_cat_id = $value->sub_cat_id;
                    $value->user_id = $value->user_id;
                    $value->total_seconds = $value->total_seconds;
                    $value->allocated_st = $value->allocated_st;
                    $value->name = $value->name;
                    $value->slug = $value->slug;
                    //$value->subcat_name = $value->subcat_name;
                    $value->subcat_name = isset($value['subcat_name']) ? $value['subcat_name'] : '';


                    $goalstatus[$key] = $value;
                }
                return response()->json(['data' => $goalstatus, 'status' => 1, 'message' => "Success"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }
    
//    public function timestatus_new(Request $request) {
//        
//        try {
//            $header = $request->header('Authorization');
//            if (!$user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['status' => 0, 'message' => "User not found"]);
//            } else {
//
//                if (empty($request->start_time) && empty($request->end_time)) {
//
//                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
//                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
//                            ->leftjoin('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
//                            ->where('goal_status.user_id', $user->id)
//                            ->get();
//                } elseif (!empty($request->start_time) && empty($request->end_time)) {
//
//                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
//                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
//                            ->join('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
//                            //->where('goal_status.user_id', $user->id)
//                            ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=', $request->start_time)
//                            ->get();
//                } else {
//                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
//                                    // ->leftJoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
//                                    ->leftJoin('sub_category', function($join) {
//                                        $join->on('goal_status.sub_cat_id', '=', 'sub_category.id');
//                                    })
//                                    ->join('category', 'goal_status.cat_id', '=', 'category.id')
//                                    ->where('goal_status.user_id', $user->id)
//                                    ->where('goal_status.start_time', '>=', $request->start_time)
//                                    ->where('goal_status.end_time', '<=', $request->end_time)->get();
//                }
//                
//                foreach ($goalstatus as $key => &$value) {
//                    $value->start_time = intval($value->start_time);
//                    $value->end_time = intval($value->end_time);
//
//                    $value->cat_id = $value->cat_id;
//                    //$value->note = $value->note;
//                    $value->note = isset($value['note']) ? $value['note'] : '';
//                    $value->goal_status_id = $value->goal_status_id;
//                    $value->sub_cat_id = $value->sub_cat_id;
//                    $value->user_id = $value->user_id;
//                    $value->total_seconds = $value->total_seconds;
//                    $value->allocated_st = $value->allocated_st;
//                    $value->name = $value->name;
//                    $value->slug = $value->slug;
//                    //$value->subcat_name = $value->subcat_name;
//                    $value->subcat_name = isset($value['subcat_name']) ? $value['subcat_name'] : '';
//
//
//                    $goalstatus[$key] = $value;
//                }
//                $goalstatus = $goalstatus->toArray();
//                usort($goalstatus, function ($a, $b) {
//                    return $a['start_time'] > $b['start_time'];
//                });
//                $goalstatusOrig = $goalstatus;
//              
////                foreach($goalstatus as $key => &$goal){
////                    $inserted['start_time'] = $goal['end_time'];
////                    $inserted['end_time'] = $goalstatus[$key+1]['start_time'];
////                    if($key<sizeof($goalstatus) && $goal['start_time']-$goalstatus[$key+1]['start_time']!=0){
////                        array_splice( $goalstatus,$key, 0, $inserted );
////                    }
////                }
//                $count = 0;
//                
//                for($i=0;$i<sizeof($goalstatus);$i++){
//                    if($i+1<sizeof($goalstatus)){
//                        $count++;
//                        $inserted = [];
//                        $inserted[$count]['note'] = "";
//                        $inserted[$count]['cat_id'] = 0;
//                        $inserted[$count]['sub_cat_id'] = 0;
//                        $inserted[$count]['user_id'] =  $goalstatus[$i]['user_id'];
//                        $inserted[$count]['goal_status_id'] =  0;
//                        $inserted[$count]['allocated_st'] = 0;
//                        $inserted[$count]['name'] = "";
//                        $inserted[$count]['slug'] = "";
//                        $inserted[$count]['subcat_name'] = "";
//                        if($i==0 && $request->start_time<=$goalstatus[$i]['start_time'] && $goalstatus[$i]['start_time']-$request->start_time!=0){
//                            $inserted[$count]['start_time'] = intval($request->start_time);
//                            $inserted[$count]['end_time'] = $goalstatus[$i]['start_time'];
//                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
//                            array_splice( $goalstatusOrig,$count, 0, $inserted );
//                            
//                        }else{
//                            $inserted[$count]['start_time'] = $goalstatus[$i]['end_time'];
//                            $inserted[$count]['end_time'] = $goalstatus[$i+1]['start_time'];
//                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
//                            if($goalstatus[$i]['end_time']-$goalstatus[$i+1]['start_time']!=0){
//                                array_splice( $goalstatusOrig,$count, 0, $inserted );
//                            }
//                        }
//                        
//                    }
//                }
//                
//                usort($goalstatusOrig, function ($a, $b) {
//                    return $a['start_time'] > $b['start_time'];
//                });
//                return response()->json(['data' => $goalstatusOrig, 'status' => 1, 'message' => "Success"]);
//            }
//        } catch (JWTException $e) {
//            return response()->json(['status' => 0, 'message' => "Token expired"]);
//        }
//    }

    public function timestatus_new(Request $request) {
        
        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            } else {

                if (empty($request->start_time) && empty($request->end_time)) {

                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
                            ->leftjoin('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
                            ->where('goal_status.user_id', $user->id)
                            ->get();
                } elseif (!empty($request->start_time) && empty($request->end_time)) {

                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                            ->join('category', 'goal_status.cat_id', '=', 'category.id')
                            ->join('sub_category', 'goal_status.sub_cat_id', '=', 'sub_category.id')
                            ->where('goal_status.user_id', $user->id)->where('goal_status.start_time', '>=', $request->start_time)
                            ->get();
                } else {
                    $goalstatus = Goalstatus::select('goal_status.cat_id', 'goal_status.note', 'goal_status.id as goal_status_id', 'goal_status.sub_cat_id', 'goal_status.user_id', 'goal_status.start_time', 'goal_status.end_time', 'goal_status.total_seconds', 'goal_status.allocated_st', 'category.name', 'category.slug', 'sub_category.name AS subcat_name')
                                    
                                    ->leftJoin('sub_category', function($join) {
                                        $join->on('goal_status.sub_cat_id', '=', 'sub_category.id');
                                    })
                                    ->join('category', 'goal_status.cat_id', '=', 'category.id')
                                    ->where('goal_status.user_id', $user->id)
                                    ->where('goal_status.end_time', '>=', $request->start_time)
                                    ->get();
                                     
                }
               
                foreach ($goalstatus as $key => &$value) {
                    if($value->start_time < $request->end_time){
                        $value->start_time = intval($value->start_time);
                        $value->end_time = intval($value->end_time);
                        $value->cat_id = $value->cat_id;
                        $value->note = isset($value['note']) ? $value['note'] : '';
                        $value->goal_status_id = $value->goal_status_id;
                        $value->sub_cat_id = $value->sub_cat_id;
                        $value->user_id = $value->user_id;
                        $value->total_seconds = $value->total_seconds;
                        $value->allocated_st = $value->allocated_st;
                        $value->name = $value->name;
                        $value->slug = $value->slug;
                        $value->subcat_name = isset($value['subcat_name']) ? $value['subcat_name'] : '';
                        if($value->end_time>$request->end_time){
                            $value->end_time = intval($request->end_time);
                            
                        }
                        if($value->start_time<$request->start_time){
                            $value->start_time = intval($request->start_time);
                            
                        }
                        $value->total_seconds = $value->end_time-$value->start_time;
                    }else{
                        unset($goalstatus[$key]);
                    }
                }
                $goalstatus = $goalstatus->toArray();
                usort($goalstatus, function ($a, $b) {
                    return $a['start_time'] > $b['start_time'];
                });
                $goalstatusOrig = $goalstatus;
                foreach($goalstatusOrig as &$goalstatusOrigData){
                    if($goalstatusOrigData['end_time']>$request->end_time){
                        $goalstatusOrigData['end_time'] = intval($request->end_time);
                    }
                   // $goalstatusOrigData['actual_start_time'] = date('d/m/Y H:i:s',  intval($goalstatusOrigData['start_time']));
                   // $goalstatusOrigData['actual_end_time'] = date('d/m/Y H:i:s', $goalstatusOrigData['end_time']);
                }

                $count = 0;
               
                for($i=0;$i<sizeof($goalstatus);$i++){
                    $count++;
                    $inserted = [];
                    $inserted[$count]['note'] = "";
                    $inserted[$count]['cat_id'] = 0;
                    $inserted[$count]['sub_cat_id'] = 0;
                    $inserted[$count]['user_id'] =  $goalstatus[$i]['user_id'];
                    $inserted[$count]['goal_status_id'] =  0;
                    $inserted[$count]['allocated_st'] = 0;
                    $inserted[$count]['name'] = "";
                    $inserted[$count]['slug'] = "";
                    $inserted[$count]['subcat_name'] = "";
                    if(sizeof($goalstatus)>1 && $i+1<sizeof($goalstatus)){
                        
                       
                        if($i==0 && $request->start_time<=$goalstatus[$i]['start_time'] && $goalstatus[$i]['start_time']-$request->start_time!=0){
                            
                           //$inserted[$count]['actual_start_time'] = date('d/m/Y H:i:s',  intval($request->start_time));
                            $inserted[$count]['start_time'] = intval($request->start_time);
                            $inserted[$count]['end_time'] = $goalstatus[$i]['start_time'];
                           //$inserted[$count]['actual_end_time'] = date('d/m/Y H:i:s', $goalstatus[$i]['start_time']);
                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
                            array_splice( $goalstatusOrig,$count, 0, $inserted );
                            
                           // $inserted[$count]['actual_start_time'] = date('d/m/Y H:i:s',  $goalstatus[$i]['end_time']);
                            $inserted[$count]['start_time'] = intval($goalstatus[$i]['end_time']);
                            $inserted[$count]['end_time'] = intval($goalstatus[$i+1]['start_time']);
                           // $inserted[$count]['actual_end_time'] = date('d/m/Y H:i:s', $goalstatus[$i+1]['start_time']);
                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
                            if($goalstatus[$i+1]['start_time']-$goalstatus[$i]['end_time']>=0){

                                array_splice( $goalstatusOrig,$count, 0, $inserted );
                            } 
                            
                        }else if($goalstatus[$i]['end_time']<$goalstatus[$i+1]['start_time'] && $goalstatus[$i]['end_time']-$goalstatus[$i+1]['start_time']!=0 && $goalstatus[$i+1]['start_time']-$goalstatus[$i]['end_time']>=0){
                           
                        // $inserted[$count]['actual_start_time'] = date('d/m/Y H:i:s',  $goalstatus[$i]['end_time']);
                            $inserted[$count]['start_time'] = intval($goalstatus[$i]['end_time']);
                            $inserted[$count]['end_time'] = intval($goalstatus[$i+1]['start_time']);
                        // $inserted[$count]['actual_end_time'] = date('d/m/Y H:i:s', $goalstatus[$i+1]['start_time']);
                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
                            if($goalstatus[$i+1]['start_time']-$goalstatus[$i]['end_time']>=0){
                               
                                array_splice( $goalstatusOrig,$count, 0, $inserted );
                            }
                        }
                        
                    }else if(sizeof($goalstatus)== 1 && $request->start_time!=$goalstatus[$i]['start_time']){
                        //  $inserted[$count]['actual_start_time'] = date('d/m/Y H:i:s',  intval($request->start_time));
                            $inserted[$count]['start_time'] = intval($request->start_time);
                            $inserted[$count]['end_time'] = $goalstatus[$i]['start_time'];
                        //  $inserted[$count]['actual_end_time'] = date('d/m/Y H:i:s', $goalstatus[$i]['start_time']);
                            $inserted[$count]['total_seconds'] = $inserted[$count]['end_time'] - $inserted[$count]['start_time'];
                            array_splice( $goalstatusOrig,$count, 0, $inserted );

                    }
                }
                
                usort($goalstatusOrig, function ($a, $b) {
                    return $a['start_time'] > $b['start_time'];
                });
                return response()->json(['data' => $goalstatusOrig, 'status' => 1, 'message' => "Success"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }
    
    public function createsubjectivegoal(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'description' => 'required',
            ]);
            if ($validator->fails()) {
                $status = 0;
                $message = implode(" and ", $validator->errors()->all());
                return response()->json(compact('status', 'message'));
            }
            if(isset($request->id) && !empty($request->id)){
                $subjectivegoal = Subjectivegoal::select('*')
                    ->where('id', $request->id)
                    ->where('user_id',$user->id)
                    ->first();
           

                if (isset($subjectivegoal) && !empty($subjectivegoal)) {
                    if (isset($request->name) && !empty($request->name)) {
                       $subjectivegoal->name = $request->name;
                    }
                    if (isset($request->description) && !empty($request->description)) {
                        $subjectivegoal->description = $request->description;
                    }
                   
                    $subjectivegoal->save();
                    $status = 1;
                    $message = "Success";
                    return response()->json(['status' => 1, 'message' => "Updated successfully"]);
                }else{
                    return response()->json(['status' => 1, 'message' => "Subjective goal not fund"]);
                }
            }else{
                $subjectivegoal = new Subjectivegoal;
                $subjectivegoal->user_id = $user->id;
                $subjectivegoal->name = $request->name;
                $subjectivegoal->description = $request->description;
                $subjectivegoal->save();
                return response()->json(['status' => 1, 'message' => "Success"]);
            }
            

            
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }
    
    public function deletesubjectivegoal(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            }
            DB::table("subjective_goal")->where("user_id", $user->id)
                    ->where("id", $request->id)->delete();
            return response()->json(['status' => 1, 'message' => "Subjective Goal deleted Successfully"]);
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function subjectivegoallist(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['status' => 0, 'message' => "User not found"]);
            }

            $subjectivegoal = DB::table("subjective_goal as SG")->select('SG.*', DB::raw("'No' as status"))->where("user_id", $user->id)->get();

            $status = 1;
            $message = "Success";
            return response()->json(compact('subjectivegoal', 'status', 'message', 'isclicked'));
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function listoftime(Request $request) {
        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            } else {

                $category = Category::all();

                $goals = Goal::select('goal.*', 'category.name', 'category.slug', 'category.icon')
                        ->leftjoin('category', 'goal.cat_id', '=', 'category.id')
                        ->where('goal.user_id', $user->id)
                        ->get();

                $task = Task::select('task.*', 'sub_category.id', 'sub_category.name AS subcat_name')
                        ->leftjoin('sub_category', 'task.sub_cat_id', '=', 'sub_category.id')
                        //->leftjoin('goal','task.cat_id','=','goal.cat_id')
                        ->where('task.user_id', $user->id)
                        ->get();
                $data = [];

                foreach ($category as $key => $value) {
                    $value->cat_id = $value->id;
                    $data[$value->name] = $value;
                    foreach ($goals as $key => $val) {
                        if (isset($val->name)) {
                            $data[$val->name] = $val->toArray();

                            foreach ($task as $key => $tasks) {
                                if ($tasks->cat_id == $val['cat_id']) {

                                    if (isset($data[$val->name]['Subcategory']) && !empty($data[$val->name]['Subcategory'])) {

                                        $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                    } else {
                                        $data[$val->name]['Subcategory'] = [];
                                        $data[$val->name]['Subcategory'][] = $tasks->toArray();
                                    }
                                }
                            }
                        }
                    }
                }


                foreach ($data as $key => $catdatas) {
                    //print_r($catdatas['name']);exit;
                    unset($catdatas['id']);
                    $catdatas['user_id'] = isset($catdatas['user_id']) ? $catdatas['user_id'] : $user->id;
                    $catdatas['cat_id'] = isset($catdatas['cat_id']) ? $catdatas['cat_id'] : 0;
                    $catdatas['Subcategory'] = isset($catdatas['Subcategory']) ? $catdatas['Subcategory'] : [];
                    $catdatas['total_seconds'] = isset($catdatas['total_seconds']) ? $catdatas['total_seconds'] : 0;
                    $catdatas['is_active'] = isset($catdatas['is_active']) ? $catdatas['is_active'] : 1;
                    $catdatas['name'];

                    $cat[] = $catdatas;
                }
                usort($cat, function ($a, $b) {
                    return $a['cat_id'] > $b['cat_id'];
                });



                return response()->json(['data' => $cat, 'status' => 1, 'message' => "Success"]);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

    public function taskdelete(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            }
            DB::table("task")->where("user_id", $user->id)
                    ->where("cat_id", $request->cat_id)
                    ->where("sub_cat_id", $request->sub_cat_id)->delete();

            return response()->json(['status' => 1, 'message' => " Delete Task Successfully"]);
        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

//    public function summary(Request $request) {
//
//        try {
//            $header = $request->header('Authorization');
//            if (!$user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['status' => 0, 'message' => "User not found"]);
//            }
//
//
//            if ($request->type == 'D') {
//                $start_time = $request->start_time;
//                $end_time = $request->end_time;
//                $type = 1;
//            }elseif ($request->type == 'W') {
//                $start_time = $request->start_time;
//                $end_time = $request->end_time;
//                $type = 7;
//            }elseif ($request->type == 'M'){
//                $start_time = $request->start_time;
//                $end_time = $request->end_time;
//                $type = 30;
//            }else{
//                $start_time = time();
//                $start_time = time();
//            }
//                $category = Category::all();
//                $goals = Goal::select('goal.user_id', 'goal.cat_id', 'category.name', 'category.slug', 'category.icon', DB::raw('sum(goal.total_seconds) as goaltime'))
//                        ->leftjoin('category', 'goal.cat_id', '=', 'category.id')
//                        ->where('goal.user_id', $user->id)
//                        ->groupBy('goal.cat_id')
//                        ->get();
//
////                $goalstatus = Task::select('goal_status.*', 'sub_category.name', 'sub_category.slug', 'sub_category.icon')
////                        ->leftjoin('goal_status', 'goal_status.cat_id', '=', 'task.cat_id')
////                        ->leftjoin('sub_category', 'sub_category.id', '=', 'task.sub_cat_id')
////                        ->where('task.user_id', $user->id)
////                        ->where('goal_status.user_id', $user->id)
////                        ->where('goal_status.start_time', '>=', $start_time)
////                        ->where('goal_status.end_time', '<=', $end_time)
////                        //->groupBy('goal_status.sub_cat_id')
////                        ->get();
//                $goalstatus = Goalstatus::select('goal_status.*','sub_category.name','sub_category.slug','sub_category.icon')
//                  ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
//                  //->leftjoin('task','goal_status.cat_id','=','task.cat_id')
//                  ->where('goal_status.user_id', $user->id)
//                ->where('goal_status.start_time', '>=' , $request->start_time)
//                ->where('goal_status.end_time','<=', $request->end_time)
//                 
//                  ->groupBy('goal_status.sub_cat_id')
//                   ->get();
//                $data = [];
//              
//                //$task = Task::all();
//                $task = Task::select('task.*','sub_category.name','sub_category.slug','sub_category.icon')
//                  ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
//                ->get();
//                $task = $task->toArray();
//                $goals = $goals->toArray();
//                $category = $category->toArray();
//                $goalstatus = $goalstatus->toArray();
//                $taskData = [];
//                foreach($task as $taskArr){
//                    foreach ($goalstatus as $key => $goalstate) {
//                       
//                        if ($goalstate['cat_id'] == $taskArr['cat_id'] && $goalstate['sub_cat_id'] == $taskArr['sub_cat_id']) {
//
//                            $taskArr['start_time'] = 0;
//                            $taskArr['end_time'] = 0;
//                            $taskArr['allocated_st'] = 0;
//                            $taskArr['actualtime'] = 0;
//                            $taskData[] = $taskArr;
//                            
//
//                        }
//                    
//                    }
//                }
//                $goalstatus = array_merge($goalstatus,$taskData);
//               // print_r($goalstatus);exit;
//                foreach ($goals as $key => $val) {
//                    $data[$val['name']] = $val;
//                    $times = DB::table('goal_status')
//                            ->where('user_id', $user->id)
//                            ->where('cat_id', $val['cat_id'])
//                            ->where('start_time','>=', $start_time)
//                            ->where('end_time','<=', $end_time)
//                            ->sum('total_seconds');
//                    $data[$val['name']]['actualtime'] = $times;
//                    
//                    foreach ($goalstatus as $key => $goalstate) {
//                       
//                        if ($goalstate['cat_id'] == $val['cat_id']) {
//
//                            $goalstate['start_time'] = intval($goalstate['start_time']);
//                            $goalstate['end_time'] = intval($goalstate['end_time']);
//                            
//                            $goalstate['allocated_st'] = intval($goalstate['allocated_st']);
//                            $goalstate['note'] = isset($goalstate['note']) ? $goalstate['note'] : '';
//                            $goalstate['slug'] = isset($goalstate['slug']) ? $goalstate['slug'] : '';
//                            $goalstate['icon'] = isset($goalstate['icon']) ? $goalstate['icon'] : '';
//                            $goalstate['name'] = isset($goalstate['name']) ? $goalstate['name'] : '';
//                            
//                            $data[$val['name']]['Subcategory'][] = $goalstate;
//                        }
//                    }
//                }   
//                
//                foreach($category as $catArr){
//                    if(!isset($data[$catArr['name']])){
//                        $catArr['goaltime'] = 0;
//                        $catArr['actualtime'] = 0;
//                        $data[$catArr['name']] = $catArr;
//                    }
//                }
//                
//                foreach ($data as $key => $catdatas) {
//
//                    $catdatas['goaltime'] = intval($catdatas['goaltime'])*$type;
//                    $catdatas['actualtime'] = intval($catdatas['actualtime']);
//                    //$catdatas['goaltime'] = intval($catdatas['goaltime']/3600);
//                    // $catdatas['actualtime'] = intval($catdatas['actualtime']/3600);
//                    $catdatas['Subcategory'] = isset($catdatas['Subcategory']) ? $catdatas['Subcategory'] : [];
//
//                    $cat[] = $catdatas;
//                }
//
//                return response()->json(['data' => $cat, 'status' => 1, 'message' => "Success"]);
//
//        } catch (JWTException $e) {
//            return response()->json(['status' => 0, 'message' => "Token expired"]);
//        }
//    }

     public function summary(Request $request) {

        try {
            $header = $request->header('Authorization');
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status' => 0, 'message' => "User not found"]);
            }


            if ($request->type == 'D') {
                $start_time = $request->start_time;
                $end_time = $request->end_time;
            }elseif ($request->type == 'W') {
                $start_time = $request->start_time;
                $end_time = $request->end_time;
            }elseif ($request->type == 'M'){
                $start_time = $request->start_time;
                $end_time = $request->end_time;
            }else{
                $start_time = time();
                $end_time = time();
            }
                $category = Category::all();
                $goals = Goal::select('goal.user_id', 'goal.cat_id', 'category.name', 'category.slug', 'category.icon', DB::raw('sum(goal.total_seconds) as goaltime'))
                        ->leftjoin('category', 'goal.cat_id', '=', 'category.id')
                        ->where('goal.user_id', $user->id)
                        ->groupBy('goal.cat_id')
                        ->get();

                $goalstatus = Goalstatus::select('goal_status.*','sub_category.name','sub_category.slug','sub_category.icon')
                ->leftjoin('sub_category','goal_status.sub_cat_id','=','sub_category.id')
                ->where('goal_status.user_id', $user->id)
                ->where('goal_status.start_time', '>=' , "$start_time")
                ->where('goal_status.end_time','<=', "$end_time")
                ->groupBy('goal_status.sub_cat_id')
                ->get();
                
                $task = Task::select('task.*','sub_category.name','sub_category.slug','sub_category.icon')
                    ->leftjoin('sub_category','task.sub_cat_id','=','sub_category.id')
                    ->where('task.user_id', $user->id)
                    ->get();
                $data = [];
                $task = $task->toArray();
                $goals = $goals->toArray();
                $category = $category->toArray();
                $goalstatus = $goalstatus->toArray();
                
                $taskData = [];
                
                foreach($task as $taskArr){
                    $taskArr['start_time'] = 0;
                    $taskArr['end_time'] = 0;
                    $taskArr['allocated_st'] = 0;
                    $taskArr['actualtime'] = 0;
                    $times = DB::table('goal_status')
                        ->where('user_id', $user->id)
                        ->where('sub_cat_id', $taskArr['sub_cat_id'])
                        ->where('start_time','>=', "$start_time")
                        ->where('end_time','<=', "$end_time")
                        ->groupBy('goal_status.sub_cat_id')
                        ->sum('total_seconds');
                    $taskArr['actualtime'] = intval($times);
                    $taskData[$taskArr['cat_id']][] = $taskArr;
                }
                
              foreach($category as &$catArr){
                    $times = DB::table('goal_status')
                            ->where('user_id', $user->id)
                            ->where('cat_id', $catArr['id'])
                            ->where('start_time','>=', "$start_time")
                            ->where('end_time','<=', "$end_time")
                            ->groupBy('goal_status.cat_id')
                            ->sum('total_seconds');
                    
                    $catArr['actualtime'] = intval($times);
                    $catArr['goaltime'] = 0;
                    if(isset($goals) && !empty($goals)){
                        foreach($goals as $goal){

                            if($goal['cat_id']==$catArr['id']){
                                 $catArr['goaltime'] = intval($goal['goaltime']);
                            }
                            
                        }
                    }
                    
                    $catArr['Subcategory'] = isset($taskData[$catArr['id']])?$taskData[$catArr['id']]:[];
                    $catArr['cat_id'] = $catArr['id'];
                    unset($catArr['id']);
                }

                return response()->json(['data' => $category, 'status' => 1, 'message' => "Success"]);

        } catch (JWTException $e) {
            return response()->json(['status' => 0, 'message' => "Token expired"]);
        }
    }

}
