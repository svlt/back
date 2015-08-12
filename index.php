<?php
require_once 'vendor/autoload.php';
use Klein\Klein;

$k = new Klein();

$k->respond('GET', '/ping', function ($request, $response, $service) {
	$response->json('Pong!');
});

$k->dispatch();
