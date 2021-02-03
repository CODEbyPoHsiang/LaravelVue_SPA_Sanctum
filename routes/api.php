<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/user/{email}', 'LoginController@user');
Route::middleware('auth:sanctum')->get('/user/{email}', 'LoginController@user');

Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');