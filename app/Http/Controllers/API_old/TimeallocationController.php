<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Timeallocation;

class TimeallocationController extends Controller
{
    //
    public function creategoal(Request $request){

//print_r($request['user_id']);exit;

//if ($user1 = JWTAuth::parseToken()->authenticate()) {

	//print_r($user['user_id']);exit;

    	$timeallocation = new Timeallocation;

    	$time= $request['user_id'];

    	print_r($time);exit;

    	/*$timeallocation->user_id = $request->get('user_id');
    	$timeallocation->cat_id = $request->get('cat_id');
    	$timeallocation->sub_cat_id = $request->get('sub_cat_id');
    	$timeallocation->start_time = $request->get('start_time');
    	$timeallocation->end_time = $request->get('end_time');
    	$timeallocation->note = $request->get('note');
    	$timeallocation->total_seconds = $request->get('total_seconds');*/

    	

    	$timeallocation->user_id = $request->get('user_id');
    	$timeallocation->cat_id = $request->get('cat_id');
    	$timeallocation->sub_cat_id = $request->get('sub_cat_id');
    	$timeallocation->start_time = $request->get('start_time');
    	$timeallocation->end_time = $request->get('end_time');
    	$timeallocation->note = $request->get('note');
    	$timeallocation->total_seconds = $request->get('total_seconds');
    	
    	$timeallocation->save();

    	$status = 1;
        $message = "Success";
        return response()->json(compact('status','message'));
  
/*}
else{

	print_r('hi');
}
*/

    }
}
