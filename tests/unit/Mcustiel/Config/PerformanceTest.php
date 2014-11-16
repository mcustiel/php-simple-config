<?php
namespace Unit\Config;

use Mcustiel\Config\ReadOnlyConfig;
use Mcustiel\Config\WritableConfig;

class PerformanceTest extends \PHPUnit_Framework_TestCase
{
    private $array = [
        'db' => [
            'auth' => [
                'user' => 'user',
                'pass' => 'pass',
                'theValue' => 'value',
                'otherValue' => 'value',
            ],
            'oneLevel' => [
                'twoLevels' => [
                    'threeLevels' => [
                        'theValue' => 'value',
                        'otherValue' => 'value',
                    ],
                    'theValue' => 'value',
                    'otherValue' => 'value',
                ],
                'theValue' => 'value',
                'otherValue' => 'value',
            ],
            'name' => 'dbName',
            'theValue' => 'value',
            'otherValue' => 'value',
        ],
        'isolated' => 'value',
        'theValue' => 'value',
        'otherValue' => 'value',
    ];

    public function testCreateOfReadOnlyConfig()
    {
        $config = null;
        $init = microtime(true);
        for ($i = 1; $i < 15000; $i++) {
            $config = new ReadOnlyConfig($this->array);
        }
        echo ">>> ReadOnlyConfig constructed 15000 times in: " . (microtime(true) - $init) . PHP_EOL;
    }

    public function testCreateOfWriteableConfig()
    {
        $config = null;
        $init = microtime(true);
        for ($i = 1; $i < 15000; $i++) {
            $config = new WritableConfig($this->array);
        }
        echo ">>> WritableConfig constructed 15000 times in: " . (microtime(true) - $init) . PHP_EOL;
    }

    public function testReadsOfReadableConfig()
    {
        $config = new ReadOnlyConfig($this->array);
        $value = null;
        $init = microtime(true);
        for ($i = 1; $i < 15000; $i++) {
            $value = $config
                ->get('db')
                ->get('oneLevel')
                ->get('twoLevels')
                ->get('threeLevels')
                ->get('otherValue');
        }
        echo ">>> ReadOnlyConfig get called 15000 times in: " . (microtime(true) - $init) . PHP_EOL;
    }

    public function testReadsOfWritableConfig()
    {
        $config = new WritableConfig($this->array);
        $value = null;
        $init = microtime(true);
        for ($i = 1; $i < 15000; $i++) {
            $value = $config
            ->get('db')
            ->get('oneLevel')
            ->get('twoLevels')
            ->get('threeLevels')
            ->get('otherValue');
        }
        echo ">>> WritableConfig get called 15000 times in: " . (microtime(true) - $init). PHP_EOL;
    }
}
