<?php

namespace Models\Base;

class File extends \Models\Base
{
	protected $fieldConf = [
			'folder' => [
				'type' => 'VARCHAR512',
				'nullable' => true
			],
			'original_name' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'name' => [
				'type' => 'VARCHAR512',
				'nullable' => true
			],
			'driver' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'deleted' => [
				'type' => 'INT1',
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
			'user' => [
				'has-many' => ['\Models\Base\User', 'avatar']
			]
		],
		$table = 'file';
}
