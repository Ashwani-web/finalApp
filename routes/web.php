<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/redirectgoogle', 'SocialAuthGoogleController@redirect');
Route::get('/callbackgoogle', 'SocialAuthGoogleController@callback');

Route::get('/redirectfacebook', 'SocialAuthFacebookController@redirect');
Route::get('/callbackfacebook', 'SocialAuthFacebookController@callback');

Route::get('/redirectlinkedin', 'SocialAuthLinkedInController@redirect');
Route::get('/callbacklinkedin', 'SocialAuthLinkedInController@callback');

Route::post('categoryList','API\CategoryController@categorylist');

//Route::get('/callbacklinkedin', 'API\SocialAuthController@register');

Route::get('/home', 'HomeController@index')->name('home');
