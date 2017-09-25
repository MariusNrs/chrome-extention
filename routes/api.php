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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/app', 'middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'projects'], function (){
        Route::get('/', 'api\\APIController@getProjects');
        Route::post('/{project_id}/candidate', 'api\\APIController@addCandidateToProject');
        Route::delete('/{project_id}/candidate/{candidate_id}','api\\APIController@deleteCandidate');
    });

    Route::group(['prefix' => 'candidates'], function (){
        Route::get('/','api\\APIController@getCandidate');
        Route::post('/','api\\APIController@createCandidate');
        Route::post('/{id}','api\\APIController@updateCandidate');
    });
});
