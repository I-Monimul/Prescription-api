<?php

/**
 * This is the default definition format
 * you can overrride this definition in app/routes/{version}/ folder
 * the app will then patch your definition with this to fix any missing routes
 */
$defaultDefinition = array(
	// The main endpoint that will be hit
	"endpoint" => "[url endpoint]",

	// The controller that will handle this endpoint
	"controller" => "[name of controller, no namespaces]",

	// For normal routes
	"routes" => array(
		// array keys define the HTTP verb
		'head' => array(
			'/' => 'get',
			'/@id' => 'getOne'
		),

		'get' => array(
			// First parameter is the route
			// Second parameter is the function name of the Controller.
			'/' => 'get',
			'/@id' => 'getOne'
		),
		'post' => array(
			'/' => 'post'
		),
		'put' => array(
			'/@id' => 'put'
		),
		'delete' => array(
			'/@id' => 'delete'
		),
	)

);

/**
 * This loads route definitions from the app/routes/ directory and
 * converts them into F3 routes
 */
$DS = DIRECTORY_SEPARATOR;
$apiVersions = glob(__DIR__ . $DS . '..' . $DS . 'routes' . $DS . 'v*', GLOB_ONLYDIR);
foreach ($apiVersions as $av) {
	$definitionFiles = scandir($av);
	$_p = explode($DS, $av);
	$version = end($_p);
	foreach ($definitionFiles as $definitionFile) {
		$pathinfo = pathinfo($definitionFile);
		//Only include php files that don't start with a .
		if ($pathinfo['extension'] === 'php' && substr($definitionFile, 0, 1) !== ".") {
			$definition = include($av . $DS . $definitionFile);
			$_d = explode(".", $definitionFile);
			$stub = reset($_d);

			//fill in controller and endpoints
			$generatedDefinition = array_replace_recursive($defaultDefinition, array(
				"endpoint" => $stub,
				"controller" => ucfirst($stub)
			));

			//sanity check
			if (!is_array($definition)) {
				$definition = array();
			}
			// extend default route definition
			$def = array_replace_recursive($generatedDefinition, $definition);


			//set the routes based on definition
			if (isset($def["routes"]) && count($def["routes"]) > 0) {
				foreach ($def["routes"] as $verb => $routes) {

					$_prefix = '/' . $version . '/' . $def["endpoint"];
					$_handler = 'Controllers\\' . str_replace('.', '_', $version) . '\\' . $def["controller"];

					if (is_array($routes) && count($routes) > 0) {
						foreach ($routes as $k => $v) {
							if ($v) {
								$_route = strtoupper($verb) . ' ' . $_prefix . ($k == '/' ? '' : $k);
								$_routeHandler = $_handler . '->' . $v;
								$app->route($_route, $_routeHandler);
							}
						}
					}
				}
			}
		}
	}
}
