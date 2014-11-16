<?php
$config = array(
    'PRODUCTION' => array(
        'DB' => array(
            'user' => 'root',
            'pass' => 'root',
            'host' => 'localhost'
        )
    ),
    'b' => 'notAnArray',
    'c' => 'alsoNotAnArray',
    'a' => [
        'property' => [
            'value',
            'deeper' => 'deeperValue'
        ],
    ]
);
