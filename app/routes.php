<?php

$router = App::router();

// Root
$router->respond('GET', '/', function($request, $response, $service) {
	$response->json(array('message' => 'This is the svlt/back base route.'));
});

// Ping
$router->respond('GET', '/ping', function($request, $response, $service) {
	$response->noCache()->json('Pong!');
});

// Users
$router->with('/u/[a:username]', function() use($router) {
	$router->respond('GET', '.json', array('Controller\\User', 'base'));
	$router->respond('GET', '/key.json', array('Controller\\User', 'key'));
});

// Handle errors
$router->onHttpError(function ($code, $router) {
	switch($code) {
		case 404:
			$router->response()->json(array(
				'error' => 'HTTP/1.1 404 Not Found',
				'path' => $router->request()->pathname(),
			));
			break;
		default:
			$router->response()->json(array(
				'error' => $code,
			));
	}
});

$router->dispatch();
