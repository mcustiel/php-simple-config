<?php
namespace Unit\Config;

use Mcustiel\Config\ConfigLoader;

class ConfigLoaderWithCacherTest extends \PHPUnit_Framework_TestCase
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
     * Mock
     * @var Mcustiel\Config\ConfigCacher
     */
    private $cacher;
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
        $this->cacher = $this->getMockBuilder('\Mcustiel\\Config\\Cacher')
            ->disableOriginalConstructor()
            ->getMOck();
        $this->config = $this->getMockBuilder('\Mcustiel\\Config\\Config')
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
            ->method('cacheConfig')
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
            ->method('getCachedConfig')
            ->will($this->returnValue($value));
    }
}
