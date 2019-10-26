<?php
namespace Models\Base;

class Referee extends \Models\Base
{
    protected $fieldConf = [
        'referee' => [
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
    $table = 'referee';

}