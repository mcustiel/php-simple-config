<?php
namespace Tests\config;

use mcustiel\config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $configArray;

    /**
     * The object under test
     * @var \mcustiel\config\Config
     */
    private $config;

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
        $this->config = new Config($this->configArray);
    }

    public function testGetFullConfigAsArray()
    {
        $this->assertEquals($this->configArray, $this->config->getFullConfigAsArray());
    }

    public function testGetFullConfigAsObject()
    {
        $config = new \stdClass();
        $config->db = new \stdClass();
        $config->db->auth = new \stdClass;
        $config->db->auth->user = 'user';
        $config->db->auth->pass = 'pass';
        $config->db->name = 'dbName';
        $config->isolated = 'value';
        $this->assertEquals($config, $this->config->getFullConfigAsObject());
    }

    public function testGetWithScalarValue()
    {
        $this->assertEquals('value', $this->config->get('isolated'));
    }

    /**
     * @expectedException mcustiel\config\exception\ConfigException
     * @expectedExceptionMessage The selected key does not exist
     */
    public function testGetWithNonExistentKey()
    {
        $this->assertEquals('value', $this->config->get('potato'));
    }

    public function testGetWithConfigValue()
    {
        $db = $this->config->get('db');
        $this->assertInstanceOf('\\mcustiel\\config\\Config', $db);
        $this->assertEquals($this->configArray['db'], $db->getFullConfigAsArray());
    }

    public function testGetWithConfigValueMultiLevel()
    {
        $this->assertEquals($this->configArray['db']['auth'],
            $this->config->get('db')->get('auth')->getFullConfigAsArray());
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
        $this->markTestIncomplete(
            'I have to decide if this is something to leave as'
            + ' it is or I will change design to support it'
        );
        var_export($this->config->get('db'));
        $this->config->get('db')->set('name', 'anotherName');
        var_export($this->config->get('db'));
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
