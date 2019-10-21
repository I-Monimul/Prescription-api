<?php

namespace Models\Base;

class Duration extends \Models\Base
{
	protected $fieldConf = [
			'duration' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'created' => [
				'type' => 'DATETIME',
				'nullable' => true
			],
			'modified' => [
				'type' => 'DATETIME',
				'nullable' => true
			],
			'deleted' => [
				'type' => 'INT1',
				'nullable' => true
			]
		],
		$table = 'duration';
}
