<?php
namespace Functional\Config;

use Mcustiel\Config\ConfigLoader;
use Mcustiel\Config\Cacher;
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
        if (!is_dir($this->cacherConfig->path)) {
            mkdir($this->cacherConfig->path, 0777, true);
        }
    	foreach (scandir($this->cacherConfig->path) as $file) {
    		if (!is_dir($this->cacherConfig->path . $file)) {
    			unlink($this->cacherConfig->path . $file);
    		}
    	}
    }

    public function testReaderWithPhpCache()
    {
        $loader = new ConfigLoader(FIXTURES_PATH . '/test.ini',
            new IniReader(),
            new Cacher($this->cacherConfig->path, 'test.ini')
        );
        // Parse original config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
        // Parse cacher config
        $config = $loader->load();
        $this->checkGeneratedConfigIsCorrect($config);
    }
}
