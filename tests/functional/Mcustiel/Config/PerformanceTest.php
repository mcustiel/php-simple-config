<?php
namespace Functional\Config;

use Mcustiel\Config\Drivers\Reader\php\Reader as PhpReader;
use Mcustiel\Config\Drivers\Reader\ini\Reader as IniReader;
use Mcustiel\Config\Drivers\Reader\json\Reader as JsonReader;
use Mcustiel\Config\Drivers\Reader\yaml\Reader as YamlReader;
use Mcustiel\Config\Cacher;
use Mcustiel\Config\ConfigLoader;

class PerformanceTest extends \PHPUnit_Framework_TestCase
{
    public function testPerformanceWithoutCacheForDifferentReaders()
    {
        $readers = [
            FIXTURES_PATH . "/test.php" => new PhpReader(),
            FIXTURES_PATH . "/test.ini" => new IniReader(),
            FIXTURES_PATH . "/test.json" => new JsonReader(),
            FIXTURES_PATH . "/test.yml" => new YamlReader()
        ];
        $cyclesCount = [
            15000
        ];

        foreach ($readers as $filename => $reader) {
            $loader = new ConfigLoader($filename, $reader);
            foreach ($cyclesCount as $cycles) {
                $start = microtime(true);
                for ($i = $cycles; $i > 0; $i --) {
                    $loader->load();
                }
                echo "\n{$cycles} cycles executed in " . (microtime(true) - $start)
                    . " seconds for " . get_class($reader) . " without cache \n";
            }
        }
    }

    public function testPerformanceWithCacheForDifferentReaders()
    {
        $readers = [
            FIXTURES_PATH . "/test.php" => new PhpReader(),
            FIXTURES_PATH . "/test.ini" => new IniReader(),
            FIXTURES_PATH . "/test.json" => new JsonReader(),
            FIXTURES_PATH . "/test.yml" => new YamlReader()
        ];
        $cyclesCount = [
            15000,
        ];

        foreach ($readers as $filename => $reader) {
            $loader = new ConfigLoader(
                $filename,
                $reader,
                new Cacher(FIXTURES_PATH . '/cache/', pathinfo($filename, PATHINFO_BASENAME))
            );
            foreach ($cyclesCount as $cycles) {
                $start = microtime(true);
                for ($i = $cycles; $i > 0; $i --) {
                    $loader->load();
                }
                echo "\n{$cycles} cycles executed in " . (microtime(true) - $start)
                    . " seconds for " . get_class($reader) . " with cache \n";
            }
        }
    }
}
