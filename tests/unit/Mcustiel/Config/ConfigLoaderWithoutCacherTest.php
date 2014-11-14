<?php
namespace Tests\config;

use mcustiel\config\ConfigLoader;

class ConfigLoaderWithoutCacherTest extends \PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = 'config.cfg';
    /**
     * Mock
     * @var \mcustiel\config\ConfigReader
     */
    private $reader;
    /**
     * Mock
     * @var \mcustiel\config\Config
     */
    private $config;
    /**
     * Object underTest
     * @var \mcustiel\config\ConfigLoader
     */
    private $loader;

    public function setUp()
    {
        $this->reader = $this->getMockBuilder('\\mcustiel\\config\\ConfigReader')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->config = $this->getMockBuilder('\\mcustiel\\config\\Config')
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
