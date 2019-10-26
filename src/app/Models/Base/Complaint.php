<?php
namespace Models\Base;

class Complaint extends \Models\Base
{
    protected $fieldConf = [
        'complaint' => [
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
    $table = 'complaint';

}