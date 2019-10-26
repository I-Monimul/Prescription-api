<?php
namespace Models\Base;

class Apikey extends \Models\Base
{
    protected $fieldConf = [
        'key' => [
            'type' => 'VARCHAR128',
            'nullable' => true
        ],
        'user' => [
            'belongs-to-one' => '\Models\Base\User'
        ],
        'user_agent' => [
            'type' => 'VARCHAR256',
            'nullable' => true
        ],
        'active' => [
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
        ]
    ],
    $table = 'apikey';

}