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

class WriterTest extends BaseFunctional
{
    private $configToWrite;

    public function setUp()
    {
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

    public function testPhpWriter()
    {
	if (file_exists(FIXTURES_PATH . '/test-written.php')) {
		unlink(FIXTURES_PATH . '/test-written.php');
	}
        $writer = new PhpWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . '/test-written.php');
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.php'));

        $config = $this->loadPhpConfig('/test-written.php');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testJsonWriter()
    {
	if (file_exists(FIXTURES_PATH . '/test-written.json')) {
                unlink(FIXTURES_PATH . '/test-written.json');
        }
        $writer = new JsonWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . '/test-written.json');
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.json'));

        $config = $this->loadJsonConfig('/test-written.json');
        $this->checkGeneratedConfigIsCorrect($config);
    }

    public function testIniWriter()
    {
	if (file_exists(FIXTURES_PATH . '/test-written.ini')) {
                unlink(FIXTURES_PATH . '/test-written.ini');
        }
        $writer = new IniWriter($this->configToWrite);
        $writer->write(FIXTURES_PATH . "/test-written.ini");
        $this->assertTrue(file_exists(FIXTURES_PATH . '/test-written.ini'));

        $config = $this->loadIniConfig('/test-written.ini');
        $this->checkGeneratedConfigIsCorrect($config);
    }
}
