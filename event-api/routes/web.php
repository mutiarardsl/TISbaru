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
// routes/web.php
$router->group(['middleware' => 'api.auth', 'prefix' => 'api'], function () use ($router) {
    $router->get('events', 'EventController@index');
    $router->post('events', 'EventController@store');
    $router->get('events/{id}', 'EventController@show');
    $router->put('events/{id}', 'EventController@update');
    $router->delete('events/{id}', 'EventController@destroy');
    
    // Tambahkan endpoint untuk kategori event dan pendaftaran
    $router->get('categories', 'EventCategoryController@index');
    $router->post('events/{eventId}/register', 'EventRegistrationController@register');
});