<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Aktifkan middleware untuk endpoint yang memerlukan auth
$router->group(['middleware' => 'api.auth', 'prefix' => 'api'], function () use ($router) {
    // Endpoint yang memerlukan authentication
    $router->post('events', 'EventController@store');
    $router->put('events/{id}', 'EventController@update');
    $router->delete('events/{id}', 'EventController@destroy');
});

// Endpoint publik (tidak perlu auth)
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('events', 'EventController@index');
    $router->get('events/{id}', 'EventController@show');
    $router->get('categories', 'EventCategoryController@index');
    $router->post('events/{eventId}/register', 'EventRegistrationController@register');
});

$router->get('test', function() {
    return response()->json(['message' => 'API is working', 'time' => now()]);
});