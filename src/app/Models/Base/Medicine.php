<?php
namespace Models\Base;

class Medicine extends \Models\Base
{
    protected $fieldConf = [
        'medicine' => [
            'type' => 'VARCHAR128',
            'nullable' => true
        ],
        'generics' => [
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
    $table = 'medicine';

}