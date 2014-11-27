<?php
namespace Unit\Config;

use Mcustiel\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $configArray;

    /**
     * The object under test
     * @var Mcustiel\Config\Config
     */
    private $config;
    private $converter;

    public function setUp()
    {
        $this->configArray = [
            'db' => [
                'auth' => [
                    'user' => 'user',
                    'pass' => 'pass',
                ],
                'name' => 'dbName',
            ],
            'isolated' => 'value',
        ];
        $this->converter = $this->getMock('Mcustiel\Config\Util\RawConfigToArrayConverter');
        $this->config = new Config($this->configArray);
        $this->config->setConverter($this->converter);
    }

    public function testGetFullConfigAsArray()
    {
        $this->converter
            ->method('convert')
            ->with($this->configArray)
            ->will($this->returnValue($this->configArray));
        $this->assertEquals($this->configArray, $this->config->getFullConfigAsArray());
    }

    public function testGetWithScalarValue()
    {
        $this->assertEquals('value', $this->config->get('isolated'));
    }

    /**
     * @expectedException Mcustiel\Config\Exception\ConfigKeyDoesNotExistException
     * @expectedExceptionMessage The key potato does not exist in config
     */
    public function testGetWithNonExistentKey()
    {
        $this->assertEquals('value', $this->config->get('potato'));
    }

    public function testGetWithConfigValue()
    {
        $db = $this->config->get('db');
        $this->assertInstanceOf('\Mcustiel\\config\\Config', $db);
        $this->converter
            ->method('convert')
            ->will($this->returnValue(['key' => 'value']));
        $db->setConverter($this->converter);
        $this->assertEquals(['key' => 'value'], $db->getFullConfigAsArray());
    }

    public function testGetWithConfigValueMultiLevel()
    {
        $this->assertEquals($this->configArray['db']['auth']['user'],
            $this->config->get('db')->get('auth')->get('user'));
    }

    public function testSetValue()
    {
        $this->config->set('isolated', 'anotherValue');
        $this->assertEquals('anotherValue', $this->config->get('isolated'));
    }

    public function testSetValueMultilevel()
    {
        $this->config->get('db')->set('name', 'anotherName');
        $this->assertEquals('anotherName', $this->config->get('db')->get('name'));
    }

    public function testSetNotExistentKey()
    {
        $this->config->set('newKey', 'newValue');
        $this->assertEquals('newValue', $this->config->get('newKey'));
    }

    public function testSetNotExistentKeyOfTypeConfig()
    {
        $config = new Config([]);
        $config->set('aKey', 'aValue');
        $this->config->set('newKey', $config);
        $this->assertEquals('aValue', $this->config->get('newKey')->get('aKey'));
    }
}
