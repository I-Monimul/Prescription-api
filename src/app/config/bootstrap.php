<?php

$app = \Base::instance();

// F3 autoloader for application business code
$app->set('AUTOLOAD', __DIR__ . '/../');

//load config
$config = require_once(__DIR__ . '/config.php');

//load services
require __DIR__ . '/services.php';

//load options
require __DIR__ . '/options.php';

//load routes
require __DIR__ . '/routes.php';

//in-app error handling
$app->set('ONERROR', function ($app) {

	if ($app->get('ERROR.code') == '404' || $app->get('ERROR.code') == '405') {
		throw new Exceptions\HTTPException(
			'Not Found.',
			404,
			array(
				'dev' => 'That route was not found on the server.',
				'internalCode' => '',
				'more' => 'Check route for mispellings.'
			)
		);

		return;
	}

	$msg = "[ERROR][" . $app->get('ERROR.code') . "] " . $app->get('ERROR.text');
	$app->get('LOGGER')->write($msg);
	if (K_ENV == K_ENV_PRODUCTION) {
		if ($app->get('ERROR.code') == '500') {
			$app->get('LOGGER')->write(print_r($app->get('ERROR.trace'), true));
		}
		send500Response();
	} else {
		echo '<h4>' . $msg . '</h4>';
		echo $app->get('ERROR.trace');
	}
});

return $app;
