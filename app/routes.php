<?php

$router = App::router();

// Root
$router->respond('GET', '/', function($request, $response, $service) {
	$response->json(array('message' => 'This is the svlt/back base route.'));
});

// Users
$router->with('/u/[a:username]', function() use($router) {

	// User basic info
	$router->respond('GET', '/?', function($request, $response, $service) use($router) {

		// Load user
		$user = QB::table('user')
				->select(array('username', 'name', 'fingerprint'))
				->find($request->username, 'username');
		if(!$user) {
			$router->abort(404);
		}

		$response->json($user);

	});

	// User public key
	$router->respond('GET', '/key/?', function($request, $response, $service) use($router) {

		// Load user
		$user = QB::table('user')->find($request->username, 'username');
		if(!$user) {
			$router->abort(404);
		}

		$routerey = QB::table('user_key')
				->select(array('fingerprint', 'key'))
				->where('type', 'public')
				->where('user_id', $user->id)
				->first();

		$response->json($routerey);

	});

});

// Ping
$router->respond('GET', '/ping', function($request, $response, $service) {
	$response->noCache()->json('Pong!');
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
