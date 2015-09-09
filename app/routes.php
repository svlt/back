<?php
use Klein\Klein as Router;

$k = new Router();

// Root
$k->respond('GET', '/', function($request, $response, $service) {
	$response->json(array('message' => 'This is the svlt/back base route.'));
});

// Users
$k->with('/u/[a:username]', function() use($k) {

	// User basic info
	$k->respond('GET', '/?', function($request, $response, $service) use($k) {

		// Load user
		$user = QB::table('user')
				->select(array('username', 'name', 'fingerprint'))
				->find($request->username, 'username');
		if(!$user) {
			$k->abort(404);
		}

		$response->json($user);

	});

	// User public key
	$k->respond('GET', '/key/?', function($request, $response, $service) use($k) {

		// Load user
		$user = QB::table('user')->find($request->username, 'username');
		if(!$user) {
			$k->abort(404);
		}

		$key = QB::table('user_key')
				->select(array('fingerprint', 'key'))
				->where('type', 'public')
				->where('user_id', $user->id)
				->first();

		$response->json($key);

	});

});

// Ping
$k->respond('GET', '/ping', function($request, $response, $service) {
	$response->noCache()->json('Pong!');
});

// Handle errors
$k->onHttpError(function ($code, $router) {
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

$k->dispatch();
