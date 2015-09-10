<?php

$router = App::router();

// Root
$router->get('/', function($request, $response, $service) {
	$response->json(['message' => 'This is the svlt/back base route.']);
});

// Ping
$router->get('/ping', function($request, $response, $service) {
	$response->noCache()->json('Pong!');
});

// Users
$router->with('/u/[a:username]', function() use($router) {
	$router->get('.json', ['Controller\\User', 'base']);
	$router->get('/key.json', ['Controller\\User', 'key']);
	$router->get('/posts.json', ['Controller\\User', 'posts']);
});

// Handle errors
$router->onHttpError(function ($code, $router) {
	switch($code) {
		case 404:
			$router->response()->json([
				'error' => 'HTTP/1.1 404 Not Found',
				'path' => $router->request()->pathname(),
			]);
			break;
		default:
			$router->response()->json([
				'error' => $code,
			]);
	}
});

$router->dispatch();
