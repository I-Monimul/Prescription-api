<?php

namespace Models\Base;

class Patient extends \Models\Base
{
	protected $fieldConf = [
			'name' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'age' => [
				'type' => 'INT1',
				'nullable' => true
			],
			'gender' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'phone' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'email' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'address' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'reference' => [
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
			],
			'investigation_history' => [
				'has-many' => ['\Models\Base\Investigation_history', 'patient']
			],
			'prescription_history' => [
				'has-many' => ['\Models\Base\Prescription_history', 'patient']
			]
		],
		$table = 'patient';
}
