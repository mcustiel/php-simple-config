<?php
namespace Functional\Config;

use Mcustiel\Config\Config;
use Mcustiel\Config\ConfigLoader;
use Mcustiel\Config\Drivers\Reader\php\Reader as PhpReader;
use Mcustiel\Config\Drivers\Reader\ini\Reader as IniReader;
use Mcustiel\Config\Drivers\Reader\json\Reader as JsonReader;
use Mcustiel\Config\Drivers\Writer\php\Writer as PhpWriter;
use Mcustiel\Config\Drivers\Writer\ini\Writer as IniWriter;
use Mcustiel\Config\Drivers\Writer\json\Writer as JsonWriter;
use Mcustiel\Config\Drivers\Cacher\file\php\Cacher as FilePhpCacher;
use Mcustiel\Config\Drivers\Cacher\file\serialize\Cacher as FileSerializeCacher;
use Mcustiel\Config\Drivers\Cacher\memcache\Cacher as MemcacheCacher;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    private $configToWrite;
    private $cacherConfig;

    public function setUp()
    {
        $this->cacherConfig = new \stdClass();
        $this->cacherConfig->path = FIXTURES_PATH . "/cache/";
        $this->cacherConfig->host = MEMCACHE_HOST;
        $this->cacherConfig->port = MEMCACHE_PORT;

        $this->configToWrite = new Config([
            'PRODUCTION' => [
                'DB' => [
                    'user' => 'root',
                    'pass' => 'root',
                    'host' => 'localhost',
                ],
            ],
            'b' => 'notAnArray',
            'c' => 'alsoNotAnArray',
            'a' => [
                'property' => [
                    0 => 'value',
                    'deeper' => 'deeperValue',
                ],
            ],
        ]);
    }

    public function testPhpReader()
    {
        $config = $this->loadPhpConfig('/test.php');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testIniReader()
    {
		$config = $this->loadIniConfig('/test.ini');
		$this->checkGeneratedConfigIsCorrect($config);
    }

    public function testJsonReader()
    {
		$config = $this->loadJsonConfig('/test.json');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testPhpWriter()
    {
        $writer = new PhpWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . '/test-written.php');
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.php'));

        $config = $this->loadPhpConfig('/test-written.php');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testJsonWriter()
    {
        $writer = new JsonWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . '/test-written.json');
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.json'));

        $config = $this->loadJsonConfig('/test-written.json');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testIniWriter()
    {
        $writer = new IniWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . "/test-written.ini");
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.ini'));

        $config = $this->loadIniConfig('/test-written.ini');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testReaderWithPhpCache()
    {
        $loader = new ConfigLoader(FIXTURES_PATH . "/test.ini",
            new IniReader(),
            new FilePhpCacher($this->cacherConfig)
        );
        // Parse original config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
        // Parse cacher config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testReaderWithSerializeCache()
    {
        $loader = new ConfigLoader(FIXTURES_PATH . "/test.ini",
            new IniReader(),
            new FileSerializeCacher($this->cacherConfig)
        );
        // Parse original config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
        // Parse cacher config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testReaderWithMemcacheCache()
    {
        if (TEST_MEMCACHE_CACHER) {
            $loader = new ConfigLoader(FIXTURES_PATH . "/test.ini",
                new IniReader(),
                new MemcacheCacher($this->cacherConfig)
            );
            // Parse original config
            $config = $loader->load();
            $this->checkGeneratedConfigIsCorrect($config);
            // Parse cacher config
            $config = $loader->load();
            $this->checkGeneratedConfigIsCorrect($config);
        }
    }

    private function checkGeneratedConfigIsCorrect(Config $config)
    {
        $this->assertEquals('notAnArray', $config->get('b'));
        $this->assertEquals('alsoNotAnArray', $config->get('c'));
        $this->assertEquals('value', $config->get('a')
            ->get('property')->get(0));
        $this->assertEquals('deeperValue', $config->get('a')
            ->get('property')
            ->get('deeper'));
        $this->assertEquals('root', $config->get('PRODUCTION')
            ->get('DB')
            ->get('user'));
        $this->assertEquals('root', $config->get('PRODUCTION')
            ->get('DB')
            ->get('pass'));
        $this->assertEquals('localhost', $config->get('PRODUCTION')
            ->get('DB')
            ->get('host'));
    }

    private function loadJsonConfig($file)
    {
        $reader = new JsonReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }

    private function loadPhpConfig($file)
    {
        $reader = new PhpReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }

    private function loadIniConfig($file)
    {
        $reader = new IniReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }
}
