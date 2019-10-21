<?php

//patch request headers
call_user_func(function ($app) {
	$headers = $app->get('HEADERS');
	if (!isset($headers['Content-Type'])) {
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$app->set('HEADERS.Content-Type', $_SERVER['CONTENT_TYPE']);
		}
	}
	if (!isset($headers['Content-Length'])) {
		if (isset($_SERVER['CONTENT_LENGTH'])) {
			$app->set('HEADERS.Content-Length', $_SERVER['CONTENT_LENGTH']);
		}
	}
}, $app);



//set CORS options
$app->set('CORS', [
	'origin'        => '*',
	'credentials'   => true,
	'headers'       => ['api-key', 'origin', 'x-requested-with', 'content-type', 'content-length'],
	'ttl'           => 86400
]);
// All OPTIONS requests get a 200, then die
if ($app->get('VERB') == 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: ' . $app->get("HEADERS.Access-Control-Request-Headers"));
	header('Access-Control-Allow-Methods: ' . $app->get("HEADERS.Access-Control-Request-Method"));
	header("HTTP/1.0 200 Ok");
	exit;
}


//set Cortex (Model Layer) options
$app->set('CORTEX.standardiseID', false);

//password hashing salt
$app->set('SALT', '08DD29BCA77ADD01105011030ACD9FA5');
