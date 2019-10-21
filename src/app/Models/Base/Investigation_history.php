<?php

namespace Models\Base;

class Investigation_history extends \Models\Base
{
	protected $fieldConf = [
			'patient' => [
				'belongs-to-one' => '\Models\Base\Patient'
			],
			'complaints' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'complaint_descriptions' => [
				'type' => 'TEXT',
				'nullable' => true
			],
			'examinations' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'examination_descriptions' => [
				'type' => 'TEXT',
				'nullable' => true
			],
			'investigations' => [
				'type' => 'VARCHAR128',
				'nullable' => true
			],
			'investigation_descriptions' => [
				'type' => 'TEXT',
				'nullable' => true
			],
			'diagnoses' => [
				'type' => 'TEXT',
				'nullable' => true
			],
			'diagnosis_descriptions' => [
				'type' => 'TEXT',
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
			],
			'prescription_history' => [
				'has-many' => ['\Models\Base\Prescription_history', 'investigation_history']
			]
		],
		$table = 'investigation_history';
}
