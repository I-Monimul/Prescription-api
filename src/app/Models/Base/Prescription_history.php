<?php

namespace Models\Base;

class Prescription_history extends \Models\Base
{
	protected $fieldConf = [
			'patient' => [
				'belongs-to-one' => '\Models\Base\Patient'
			],
			'investigation_history' => [
				'belongs-to-one' => '\Models\Base\Investigation_history'
			],
			'medicines' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'times' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'comments' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'durations' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'date' => [
				'type' => 'DATE',
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
		$table = 'prescription_history';
}
