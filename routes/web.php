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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', ['uses'=>'AuthController@login']);
    $router->get('verify', ['uses'=>'AuthController@verify']);

});
$router->group(['prefix' => 'api/{table}', 'middleware' => 'auth'], function () use ($router) {
    $router->get('', ['uses' => 'RestReadController@index']);
    $router->get('{id}', ['uses' => 'RestReadController@indexAt']);
    $router->get('{id}/{column}', ['uses' => 'RestReadController@indexAtColumn']);
    $router->get('w/{column}/{value}', ['uses' => 'RestReadController@indexWhere']);

    $router->post('', ['uses' => 'RestCreateController@insert']);
    // $router->post('/w/{column}/{value}', ['uses' => 'RestController@insertWhere']);

    $router->put('/{id}', ['uses' => 'RestUpdateController@update']);
    // $router->patch('/{id}/{column}', ['uses' => 'RestController@updateAtColumn']);

    $router->delete('/{id}', ['uses' => 'RestDeleteController@delete']);
});
