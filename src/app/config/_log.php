<?php

$logconfig = array(
	'folder'        => __DIR__ . '/../../runtime/logs/',
	'filename'      => 'application.log',
	'maxFileSize'   => 1024,
	'maxLogFiles'   => 5,
	'rotate'        => true,
);

return $logconfig;
