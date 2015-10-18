<?php
namespace Unit\Config;

use Mcustiel\Config\ConfigLoader;
use Mcustiel\Config\CacheConfig;
use Mcustiel\Config\ConfigReader;
use Mcustiel\Config\Config;
use Mcustiel\SimpleCache\Interfaces\CacheInterface;
use Mcustiel\SimpleCache\Types\Key;

class ConfigLoaderWithCacherTest extends \PHPUnit_Framework_TestCase
{
    const CONFIG_FILE = 'config.cfg';
    /**
     * Mock
     * @var \Mcustiel\Config\ConfigReader
     */
    private $reader;
    /**
     * Mock
     * @var \Mcustiel\Config\Config
     */
    private $config;
    /**
     * Mock
     * @var \Mcustiel\Config\CacheConfig
     */
    private $cacheConfig;
    /**
     * @var \Mcustiel\SimpleCache\Interfaces\CacheInterface
     */
    private $cache;
    /**
     * Object under test
     * @var \Mcustiel\Config\ConfigLoader
     */
    private $loader;

    public function setUp()
    {
        $this->reader = $this->getMockBuilder(ConfigReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cacheConfig = $this->getMockBuilder(CacheConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cache = $this->getMockBuilder(CacheInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loader = new ConfigLoader(self::CONFIG_FILE, $this->reader, $this->cacheConfig);
    }

    public function testLoadWithCachedData()
    {
        $this->cacheConfig
            ->expects($this->once())
            ->method('getCacheManager')
            ->willReturn($this->cache);

        $this->cacheConfig
            ->expects($this->once())
            ->method('getKey')
            ->willReturn(new Key('potato'));

        $this->trainCacherMockWithLoadReturnValue($this->config);
        $this->assertSame($this->config, $this->loader->load());
    }

    public function testLoadWithoutCachedData()
    {
        $this->cacheConfig
            ->expects($this->exactly(2))
            ->method('getCacheManager')
            ->willReturn($this->cache);

        $this->cacheConfig
            ->expects($this->exactly(2))
            ->method('getKey')
            ->willReturn(new Key('potato'));

        $this->cacheConfig
            ->expects($this->once())
            ->method('getTtl')
            ->willReturn(5001);

        $this->trainCacherMockWithLoadReturnValue(null);
        $this->reader
            ->expects($this->once())
            ->method('read')
            ->will($this->returnValue($this->config));
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->config));
        $this->reader
            ->method('getConfig')
            ->will($this->returnValue($this->config));
        $this->assertSame($this->config, $this->loader->load());
    }

    private function trainCacherMockWithLoadReturnValue($value)
    {
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($value));
    }
}
