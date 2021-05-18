<?php

require_once('./app/Router.php');
require_once('./app/Controllers/ApiController.php');

use Demo\Router;

// Currency converter from (USD | EUR | BRL) to (USD | EUR | BRL)
Router::get('/exchange/{from}/{to}', [ApiController::class, 'exchange']);

Router::error(function() {
	return response()->httpCode(400)->json([
		'error' => 'URL invalida',
		'code'  => 400,
	]);
});
