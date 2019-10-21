<?php

namespace Models\Base;

class Comment extends \Models\Base
{
	protected $fieldConf = [
			'comment' => [
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
		$table = 'comment';
}
