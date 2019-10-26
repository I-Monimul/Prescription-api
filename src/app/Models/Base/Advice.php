<?php
namespace Models\Base;

class Advice extends \Models\Base
{
    protected $fieldConf = [
        'advice' => [
            'type' => 'VARCHAR128',
            'nullable' => true
        ],
        'alternative' => [
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
    $table = 'advice';

}