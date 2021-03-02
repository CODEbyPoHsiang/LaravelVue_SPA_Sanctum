<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/user/{email}', 'LoginController@user');
Route::middleware('auth:sanctum')->get('/user/{email}', 'LoginController@user');

Route::post('/login', 'LoginController@login');
Route::post('/register', 'RegisterController@register');
Route::post('/logout', 'LoginController@logout');
Route::post("/google2fa_login", 'LoginController@checkGoogle2faOTP');

Route::post('/remove_password/{email}', 'LoginController@remove_password');


//產品清單
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', 'ProductController@index');
    Route::post('/products', 'ProductController@store');
    Route::get('/products/{id}', 'ProductController@show');
    Route::patch('/products/{id}', 'ProductController@update');
    Route::delete('/products/{id}', 'ProductController@destroy');
    Route::post('/search', 'ProductController@search');
});



Route::get('/csv', 'UbikeMap@csv');



//台北ubike相關
Route::get('/taipeiubikemap', 'UbikeMap@taipeiubikemap');
//畫面上搜尋的api
Route::post('/taipeiubikemap_search', 'UbikeMap@taipeiubikemap_search');
//上一條api得到的列表，畫面上的sno值利用此api取到單一資料，使畫面飛越
Route::get('/taipeiubikemap_full_match/{keywords}', 'UbikeMap@taipeiubikemap_full_match');


//台中ubike相關
Route::get('/taichungubikemap', 'UbikeMap@taichungubikemap');
Route::get('/taichungibikemap', 'UbikeMap@taichungibikemap');
//全部台中單車列表
Route::get('/taichungallbikemap', 'UbikeMap@taichungallbikemap');
Route::post('/taichungubikemap_search', 'UbikeMap@taichungubikemap_search');
//全部臺中單車搜尋
Route::post('/taichungallbikemap_search', 'UbikeMap@taichungallbikemap_search');
Route::get('/taichungubikemap_full_match/{keywords}', 'UbikeMap@taichungubikemap_full_match');
//點擊搜尋後觸發的api
Route::get('/taichungallbikemap_full_match/{keywords}', 'UbikeMap@taichungallbikemap_full_match');
