<?php
namespace Functional\Config;

use Mcustiel\Config\ConfigLoader;
use Mcustiel\Config\Drivers\Cacher\file\php\Cacher as FilePhpCacher;
use Mcustiel\Config\Drivers\Cacher\file\serialize\Cacher as FileSerializeCacher;
use Mcustiel\Config\Drivers\Cacher\memcache\Cacher as MemcacheCacher;
use Mcustiel\Config\Drivers\Reader\php\Reader as PhpReader;
use Mcustiel\Config\Drivers\Reader\ini\Reader as IniReader;
use Mcustiel\Config\Drivers\Reader\json\Reader as JsonReader;

class CacherTest extends BaseFunctional
{
    private $cacherConfig;

    public function setUp()
    {
        $this->cacherConfig = new \stdClass();
        $this->cacherConfig->path = FIXTURES_PATH . "/cache/";
        $this->cacherConfig->host = MEMCACHE_HOST;
        $this->cacherConfig->port = MEMCACHE_PORT;
	foreach (scandir($this->cacherConfig->path) as $file) {
		if (!is_dir($this->cacherConfig->path . $file)) {
			unlink($this->cacherConfig->path . $file);
		}
	}
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
}
