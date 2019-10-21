<?php

namespace Models\Base;

class User extends \Models\Base
{
	protected $fieldConf = [
			'name' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'surname' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'username' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'email' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'password' => [
				'type' => 'VARCHAR256',
				'nullable' => true
			],
			'role' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'avatar' => [
				'belongs-to-one' => '\Models\Base\File'
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
			'apikey' => [
				'has-many' => ['\Models\Base\Apikey', 'user']
			]
		],
		$table = 'user';
}
