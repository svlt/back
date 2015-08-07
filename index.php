<?php
/**
 * Primary front controller/router
 *
 * @package  svlt/back
 */

require_once 'mu/mu.php';
require_once 'vendor/autoload.php';

$response = (new µ)
	->get('/ping', function ($app) {
		return 'Pong!';
	})
	->run();

if(!is_array($response)) {
	$response = array(
		"status" => 200,
		"error" => null,
		"message" => $response,
	);
}

header('Content-type: application/json');
echo json_encode($response);
