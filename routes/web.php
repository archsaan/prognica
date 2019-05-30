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

//Route::get('/', function () {
//    //return view('cropper');
//    return view('auth.login');
//});
Route::get('/', 'UserController@login');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@login')->name('login');
Route::get('/register', 'UserController@register')->name('register');
Route::post('/register', 'UserController@register')->name('register');
Route::post('/create', 'UserController@create')->name('create');
Route::get('/consent', 'UserController@consent')->name('consent');
Route::post('/consent', 'UserController@consent')->name('consent');
Route::get('/logout', 'UserController@logout')->name('logout');
Route::get('/lost_password', "UserController@lostPassword")->name('lost_password');
Route::post('/lost_password', "UserController@lostPassword")->name('lost_password');
Route::get('/admin_approval/{token}', "UserController@adminApproval")->name('admin_approval');
Route::get('/verify_email/{token}', "UserController@verifyEmail")->name('verify_email');
Route::get('/reset_password/{token}', "UserController@resetPassword")->name('resetPassword');
Route::post('/reset_password/{token}', "UserController@resetPassword")->name('resetPassword');
Route::get('/resend/{user_id}', "UserController@resend")->name('resend');


Route::group(['middleware' => 'auth'], function(){
    Route::get('/choose_option', "DashboardController@chooseOption")->name('choose_option');
    Route::get('/dashboard', "DashboardController@index")->name('dashboard');
    Route::get('/download_sample', "DashboardController@DownloadSample")->name('download_sample');
    Route::get('/my_profile', "UserController@myProfile")->name('my_profile');
    Route::post('/my_profile', "UserController@myProfile")->name('my_profile'); 
    Route::post('/change_password', "UserController@changePassword")->name('change_password');
    Route::post('/update', "UserController@update")->name('update');
    Route::get('/ajax_upload_pic', 'UserController@AjaxUploadPic')->name('ajax_upload_pic');
    Route::post('/ajax_upload_pic', 'UserController@AjaxUploadPic')->name('ajax_upload_pic');
    Route::post('/upload_image', "DashboardController@UploadImage")->name('upload_image');
    Route::get('/delete_user', "UserController@deleteUser")->name('delete_user');
    Route::post('/delete_user', "UserController@deleteUser")->name('delete_user');
    Route::get('/pdf', "DashboardController@pdf")->name('pdf');
    
    
});