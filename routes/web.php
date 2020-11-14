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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'UserController@register');

    $router->post('login', 'UserController@login');

    $router->post('logut','UserController@logout');

    $router->get('me', [
        'middleware' => 'auth', 'uses'=>'UserController@me']);
    $router->post('user/posts',['middleware'=>'auth','uses'=>'UserController@createPost']);

    $router->group(['prefix'=>'user','middleware'=>'auth'],function () use ($router) {
        $router->put('posts/{post_id}','UserController@updatePost');
        $router->delete('posts/{post_id}','UserController@deletePost');
    });
});
