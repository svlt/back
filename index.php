<?php
require_once 'vendor/autoload.php';
use Klein\Klein;

$k = new Klein();

$k->respond('GET', '/', function ($request, $response, $service) {
	$response->json(array('message' => 'This is the svlt/back base route.'));
});

$k->respond('GET', '/ping', function ($request, $response, $service) {
	$response->json('Pong!');
});

$k->dispatch();
