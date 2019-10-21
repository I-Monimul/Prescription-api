<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . "/../app/helpers/error_handling.php");

try {

	$app = require_once('../app/config/bootstrap.php');

	$app->run();
} catch (Exceptions\HTTPException $ex) {
	$msg = "[HTTPException][" . $ex->getCode() . "] " . $ex->getMessage();
	error_log($msg);
	$ex->send();
} catch (PDOException $ex) {
	$msg = "[PDOException][" . $ex->getCode() . "] " . $ex->getMessage();

	error_log($msg);

	if (K_ENV == K_ENV_PRODUCTION) {
		send500Response();
	} else {
		header('HTTP/1.1 500 Internal Server Error');
		header('Status: 500 Internal Server Error');
		header('Access-Control-Allow-Origin: *');
		echo '<h4>' . $msg . '</h4>';
	}
} catch (Exception $ex) {
	$msg = "[Exception][" . $ex->getCode() . "] " . $ex->getMessage();

	error_log($msg);

	if (K_ENV == K_ENV_PRODUCTION) {
		send500Response();
	} else {
		header('HTTP/1.1 500 Internal Server Error');
		header('Status: 500 Internal Server Error');
		header('Access-Control-Allow-Origin: *');
		echo '<h4>' . $msg . '</h4>';
	}
}
