<?php

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



$router->group(["prefix" => "auth"], function() use ($router){

	$router->get('/', ["as" => "auth.index", "uses" => "AuthController@index"]);

	$router->get('callback', function () use ($router) {
	    echo "Hello World";
	});

});

$router->group(["prefix" => "warranty"], function() use ($router){

	$router->get('/list', ["as" => "warranty.list", "uses" => "WarrantyController@list"]);

	$router->post('/save', ["as" => "warranty.save", "uses" => "WarrantyController@save"]);

});