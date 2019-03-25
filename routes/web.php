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

	$router->post('/type', ["as" => "warranty.type", "uses" => "WarrantyController@getTypeViaSerial"]);

	$router->post('/check_serial_email', ["as" => "warranty.check_serial_email", "uses" => "WarrantyController@checkSerialEmail"]);

});

$router->group(["prefix" => "vehicle-info"], function() use ($router){

	$router->get('/make/list[/{keyword:[a-z|A-Z|0-9]+}]', ["as" => "vehicle.make.list", "uses" => "VehicleInfoController@getMake"]);

	$router->get('/model/list[/{keyword:[a-z|A-Z|0-9]+}]', ["as" => "vehicle.model.list", "uses" => "VehicleInfoController@getModel"]);

	$router->get('/make', ["as" => "vehicle.make.list.full", "uses" => "VehicleInfoController@makeList"]);	

	$router->post('/model', ["as" => "vehicle.model.list.full", "uses" => "VehicleInfoController@modelList"]);	

});

$router->group(["prefix" => "dealer-info"], function() use ($router){

	$router->post('/name', ["as" => "dealer.name.list", "uses" => "DealerInfoController@nameList"]);	

});