<?php

$router = App::router();

// Root
$router->route('GET /', 'Controller\\Index->root');
$router->route('GET /ping.json', 'Controller\\Index->ping');

// Users
$router->route('GET /u/@username.json', 'Controller\\User->base');
$router->route('GET /u/@username/key.json', 'Controller\\User->key');
$router->route('GET /u/@username/posts.json', 'Controller\\User->posts');
$router->route('POST /u/@username/posts.json', 'Controller\\User->post');
$router->route('POST /register.json', 'Controller\\User->register');
$router->route('POST /auth.json', 'Controller\\User->auth');
$router->route('POST /logout.json', 'Controller\\User->logout');
$router->route('GET /keystore.json', 'Controller\\User->keystore');

// Posts
$router->route('GET /post.json', 'Controller\\Post->post');
$router->route('GET /post/@id.json', 'Controller\\Post->single');

// Handle errors
$router->set('ONERROR', function(Base $f3) {
	$controller = new Controller\Index;
	switch($f3->get('ERROR.code')) {
		case 404:
			$controller->_json([
				'error' => 'HTTP/1.1 404 Not Found',
				'path' => $f3->get('PATH'),
			]);
			break;
		case 401:
			$controller->_json([
				'error' => 'HTTP/1.1 401 Unauthorized',
				'message' => 'Supply a valid `token` value to avoid this error.',
				'path' => $f3->get('PATH'),
			]);
			break;
		default:
			$controller->_json(['error' => $f3->get('ERROR.code')] + $f3->get('ERROR'));
	}
});
