<?php

namespace Models\Base;

class Examination extends \Models\Base
{
	protected $fieldConf = [
			'examination' => [
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
		$table = 'examination';
}
