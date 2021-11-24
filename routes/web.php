<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', ['uses' => 'AuthController@login']);
    $router->get('verify', ['uses' => 'AuthController@verify', 'middleware' => 'auth']);
    $router->post('photo', ['uses' => 'AuthController@uploadPicture', 'middleware' => 'auth']);
    $router->post('password', ['uses' => 'AuthController@changePassword', 'middleware' => 'auth']);
});
$router->group(['prefix' => 'api/{table}', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/count', ['uses' => 'RestReadController@index']);
    $router->get('', ['uses' => 'RestReadController@get']);
    $router->get('{id}', ['uses' => 'RestReadController@getAt']);
    $router->get('{id}/{column}', ['uses' => 'RestReadController@getAtColumn']);

    $router->post('', ['uses' => 'RestCreateController@insert']);
    // $router->post('/w/{column}/{value}', ['uses' => 'RestController@insertWhere']);

    $router->put('/{id}', ['uses' => 'RestUpdateController@update']);

    $router->post('/{id}/upload/{column}', ['uses' => 'RestUpdateController@uploadAtColumn']);

    $router->delete('/{id}', ['uses' => 'RestDeleteController@delete']);
});
