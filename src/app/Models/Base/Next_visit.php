<?php
namespace Models\Base;

class Next_visit extends \Models\Base
{
    protected $fieldConf = [
        'next_visit' => [
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
    $table = 'next_visit';

}