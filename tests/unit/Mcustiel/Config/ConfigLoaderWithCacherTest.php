<?php
namespace Tests\config;

use mcustiel\config\ConfigLoader;

class ConfigLoaderWithCacherTest extends \PHPUnit_Framework_TestCase
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
     * Mock
     * @var \mcustiel\config\ConfigCacher
     */
    private $cacher;
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
        $this->cacher = $this->getMockBuilder('\\mcustiel\\config\\ConfigCacher')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->config = $this->getMockBuilder('\\mcustiel\\config\\Config')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->loader = new ConfigLoader(self::CONFIG_FILE, $this->reader, $this->cacher);
    }

    public function testLoadWithCachedData()
    {
        $this->trainCacherMockWithLoadReturnValue($this->config);
        $this->assertSame($this->config, $this->loader->load());
    }

    public function testLoadWithoutCachedData()
    {
        $this->trainCacherMockWithLoadReturnValue(null);
        $this->reader
            ->expects($this->once())
            ->method('read')
            ->will($this->returnValue($this->config));
        $this->cacher
            ->expects($this->once())
            ->method('save')
            ->will($this->returnValue($this->config));
        $this->reader
            ->method('getConfig')
            ->will($this->returnValue($this->config));
        $this->assertSame($this->config, $this->loader->load());
    }

    private function trainCacherMockWithLoadReturnValue($value)
    {
        $this->cacher
            ->expects($this->once())
            ->method('open');
        $this->cacher
            ->expects($this->once())
            ->method('setName')
            ->with($this->equalTo(self::CONFIG_FILE));
        $this->cacher
            ->expects($this->once())
            ->method('close');
        $this->cacher
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue($value));
    }
}
