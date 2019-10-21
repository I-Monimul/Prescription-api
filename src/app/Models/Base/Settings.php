<?php

namespace Models\Base;

class Settings extends \Models\Base
{
	protected $fieldConf = [
			'name' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'value' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'display_name' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'description' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'modified' => [
				'type' => 'DATETIME',
				'nullable' => true
			]
		],
		$table = 'settings';
}
