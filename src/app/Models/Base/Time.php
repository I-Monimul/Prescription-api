<?php

namespace Models\Base;

class Time extends \Models\Base
{
	protected $fieldConf = [
			'time' => [
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
		$table = 'time';
}
