<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Registration
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');

// Login
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');

// Logout
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::post('/update/account', 'App\Http\Controllers\Controller@account')->name('account');
Route::post('/add/device', 'App\Http\Controllers\Controller@adddevice')->name('device');

Route::get('/', 'App\Http\Controllers\Controller@indexPage')->middleware('auth');
Route::post('/remove/device', 'App\Http\Controllers\Controller@removeDevice');

Route::post('/history', 'App\Http\Controllers\Controller@updateHistory')->name('history');

Route::get('/history/{operation}', 'App\Http\Controllers\Controller@getLastExecutionDate');
