<?php

$app->set('CONFIG', $config);

//Database service
$app->set('DB', call_user_func(function ($app) {
	$config = $app->get('CONFIG')['DB'];
	$dsn = $config['adapter'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];

	return new DB\SQL(
		$dsn,
		$config['username'],
		$config['password']
	);
}, $app));

//responder service
$app->set('RESPONDER', call_user_func(function ($app) {
	return \Responses\JSONResponse::instance();
}, $app));

//metadata provider service
$app->set('METADATAPROVIDER', call_user_func(function ($app) {
	return \Utils\MetadataProvider::instance();
}, $app));

//requestbody service
$app->set('REQUESTBODY', call_user_func(function ($app) {
	return \Utils\RequestBody::instance();
}, $app));


//Logger service
$app->set('LOGS', $app->get('CONFIG')['LOG']['folder']);
$app->set('LOGGER', call_user_func(function ($app) {
	return new \Utils\Log($app->get('CONFIG')['LOG']);
}, $app));
