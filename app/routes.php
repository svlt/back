<?php

$router = App::router();

// Root
$router->get('/', function($request, $response, $service) {
	$response->json([
		'message' => 'This is the Vault API. Documentation is available at https://svlt.github.io/.',
	]);
});

// Ping
$router->get('/ping.json', function($request, $response, $service) {
	$response->noCache()->json('Pong!');
});

// Users
$router->with('/u/[a:username]', function() use($router) {
	$router->get('.json', ['Controller\\User', 'base']);
	$router->get('/key.json', ['Controller\\User', 'key']);
	$router->get('/posts.json', ['Controller\\User', 'posts']);
	$router->post('/posts.json', ['Controller\\User', 'post']);
});
$router->post('/register.json', ['Controller\\User', 'register']);

// Posts
$router->with('/post', function() use($router) {
	$router->post('.json', ['Controller\\Post', 'post']);
	$router->get('/[i:id].json', ['Controller\\Post', 'single']);
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
		case 401:
			$router->response()->json([
				'error' => 'HTTP/1.1 401 Unauthorized',
				'message' => 'Supply a valid `token` value to avoid this error.',
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
