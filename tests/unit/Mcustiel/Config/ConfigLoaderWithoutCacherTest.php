<?php
namespace Unit\Config;

use Mcustiel\Config\ConfigLoader;

class ConfigLoaderWithoutCacherTest extends \PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = 'config.cfg';
    /**
     * Mock
     * @var Mcustiel\Config\ConfigReader
     */
    private $reader;
    /**
     * Mock
     * @var Mcustiel\Config\Config
     */
    private $config;
    /**
     * Object underTest
     * @var Mcustiel\Config\ConfigLoader
     */
    private $loader;

    public function setUp()
    {
        $this->reader = $this->getMockBuilder('\Mcustiel\\Config\\ConfigReader')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->config = $this->getMockBuilder('\Mcustiel\\Config\\Config')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->loader = new ConfigLoader(self::CONFIG_FILE, $this->reader);
    }

    public function testLoad()
    {
        $this->reader
            ->expects($this->once())
            ->method('read')
            ->with($this->equalTo(self::CONFIG_FILE));
        $this->reader
            ->method('getConfig')
            ->will($this->returnValue($this->config));
        $this->assertSame($this->config, $this->loader->load());
    }
}
