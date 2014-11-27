<?php
namespace Unit\Config\Util;

use Mcustiel\Config\Config;
use Mcustiel\Config\Util\RawConfigToArrayConverter;

class RawConfigToArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new RawConfigToArrayConverter();
    }

    public function testConvertsOkWhenArrayContainsConfigObject()
    {
        $config = $this->getMockBuilder('Mcustiel\Config\Config')
            ->disableOriginalConstructor()
            ->getMock();
        $config->method('getFullConfigAsArray')
            ->will($this->returnValue(['inner' => 'value']));

        $original = [
            'scalar' => 'value',
            'config' => $config
        ];

        $expected = [
            'scalar' => 'value',
            'config' => ['inner' => 'value']
        ];

        $this->assertEquals($expected, $this->converter->convert($original));
    }

    public function testConvertsOkWhenArrayIsNotMultiLevel()
    {
        $original = [
            'scalar' => 'value',
            'other' => 'value'
        ];

        $this->assertEquals($original, $this->converter->convert($original));
    }

    public function testConvertsOkWhenArrayIsMultilevel()
    {
        $original = [
            'scalar' => 'value',
            'other' => ['key' => 'value']
        ];

        $this->assertEquals($original, $this->converter->convert($original));
    }
}
