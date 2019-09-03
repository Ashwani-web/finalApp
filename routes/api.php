<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'API\UserController@register');
Route::post('update', 'API\UserController@update');
Route::post('update-profile-image','API\UserController@updateprofileimage');
Route::post('social-login', 'API\SocialAuthController@register');
Route::post('apilogin', 'API\SocialAuthController@login');
Route::post('login', 'API\UserController@login');
Route::post('details','API\UserController@getAuthenticatedUser');
Route::post('logout','API\UserController@logout');
Route::post('forgot-password','API\UserController@forgotpassword');
Route::get('forgot-password-url/','API\ForgotpasswordController@index');
Route::post('/reset','API\ForgotpasswordController@resetpassword');
Route::post('change-password','API\UserController@changepassword');
Route::get('categorylist','API\CategoryController@categorylist');
Route::post('create-category','API\CategoryController@createcategory');
Route::get('sub-categorylist','API\CategoryController@subcategorylist');

Route::get('search','API\GoalController@searchsubcategory');

 
Route::get('goal-list','API\GoalController@listofgoal');

//Route::post('goal-list','API\GoalController@listofgoal');
Route::post('create-goal','API\GoalController@creategoal');
Route::post('update-goal','API\GoalController@goalupdate');
Route::delete('delete-user','API\UserController@destroy');
Route::delete('delete-goal','API\GoalController@deletegoal');

Route::post('create-task','API\GoalController@createtask');
Route::post('delete-task','API\GoalController@taskdelete');

Route::post('createtime-entry','API\GoalController@createtime');
Route::post('update-createtime-entry','API\GoalController@updatecreatetime');
Route::post('create-subjective-goal','API\GoalController@createsubjectivegoal');
Route::get('subjective-goal-list','API\GoalController@subjectivegoallist');

Route::get('time','API\GoalController@listoftime');
Route::get('timestatus','API\GoalController@timestatus');

Route::get('summary','API\GoalController@summary');
Route::get('summary1','API\GoalController@summary1');

Route::delete('delete-createtime','API\GoalController@deletecreateTime');


 



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
