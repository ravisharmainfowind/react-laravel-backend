<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
//use App\Http\Controllers\Api\V1\RoleController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::get('employees', 'ApiController@getAllEmployees');
Route::get('employees/{id}', 'ApiController@getEmployee');
Route::post('employees', 'ApiController@createEmployee');
Route::put('employees/{id}', 'ApiController@updateEmployee');
Route::delete('employees/{id}','ApiController@deleteEmployee');
Route::post('uploadfile', 'ApiController@uploadFile');
Route::post('multiple-image-upload', 'ApiController@store');

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'role'], function () {
		Route::get('/','RoleController@index');
		Route::post('/store','RoleController@store');
		Route::get('/{id}/edit','RoleController@edit');
		Route::put('/{id}/update','RoleController@update');
		Route::put('/{id}/status','RoleController@changeStatus');
		Route::delete('/{id}/delete','RoleController@delete');
     });
});

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'category'], function () {
		Route::get('/','CategoryController@index');
		Route::get('/create','CategoryController@create');
		Route::post('/store','CategoryController@store');
		Route::get('/{id}/edit','CategoryController@edit');
		Route::post('/{id}/update','CategoryController@update');
		Route::put('/{id}/status','CategoryController@changeStatus');
		Route::delete('/{id}/delete','CategoryController@delete');
     });
});

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
	Route::group(['prefix' => 'product'], function () {
		Route::get('/','ProductController@index');
		Route::get('/create','ProductController@create');
		Route::post('/store','ProductController@store');
		Route::get('/edit/{id}','ProductController@edit');
		Route::post('/update/{id}','ProductController@update');
		Route::put('/status/{id}','ProductController@changeStatus');
		Route::delete('/delete/{id}','ProductController@delete');
	 });
});
