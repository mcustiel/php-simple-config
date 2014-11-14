<?php
namespace Mcustiel\Config;

use Mcustiel\Config\Config;
use Mcustiel\Config\ConfigLoader;

class Main
{
    public function run()
    {
		echo "###################### PHP READER ###################################\n";
        $reader = new Mcustiel\Config\Drivers\Reader\php\Reader();
		$reader->read(__DIR__ . "/resources/test.php");
        $config = $reader->getConfig();
        var_export($config->getFullConfigAsArray());
        echo PHP_EOL;
        var_export($config->getFullConfigAsObject());
        echo PHP_EOL . PHP_EOL;

		echo "###################### INI READER ###################################\n";
		$reader =  new Mcustiel\Config\Drivers\Reader\ini\Reader();
		$reader->read(__DIR__ . "/resources/test.ini");
        $config = $reader->getConfig();
		$iniConfig = $config;
        var_export($config->getFullConfigAsArray());
        echo PHP_EOL;
        var_export($config->getFullConfigAsObject());
        echo PHP_EOL . PHP_EOL;

		echo "###################### JSON READER ###################################\n";
		$reader = new Mcustiel\Config\Drivers\Reader\json\Reader();
		$reader->read(__DIR__ . "/resources/test.json");
        $config = $reader->getConfig();
        var_export($config->getFullConfigAsArray());
        echo PHP_EOL;
        var_export($config->getFullConfigAsObject());
        echo PHP_EOL . PHP_EOL;

		echo "###################### PHP WRITER ###################################\n";
		$writer = new Mcustiel\Config\Drivers\Writer\php\Writer($iniConfig);
		$writer->write(__DIR__ . "/resources/test-written.php");
		echo file_get_contents(__DIR__ . "/resources/test-written.php") . PHP_EOL . PHP_EOL;

		echo "###################### JSON WRITER ###################################\n";
		$writer = new Mcustiel\Config\Drivers\Writer\json\Writer($iniConfig);
		$writer->write(__DIR__ . "/resources/test-written.json");
		echo file_get_contents(__DIR__ . "/resources/test-written.json") . PHP_EOL . PHP_EOL;

		echo "###################### INI WRITER ###################################\n";
		$writer = new Mcustiel\Config\Drivers\Writer\ini\Writer($iniConfig);
		$writer->write(__DIR__ . "/resources/test-written.ini");
		echo file_get_contents(__DIR__ . "/resources/test-written.ini") . PHP_EOL . PHP_EOL;

		echo "###################### CONFIG ACCESS ################################\n";
		echo '$iniConfig->get("PRODUCTION"): '
		    . var_export($iniConfig->get('PRODUCTION')->getFullConfigAsArray(), true) . PHP_EOL;
		echo '$iniConfig->get("PRODUCTION")->get("DB"): '
		    . var_export($iniConfig->get('PRODUCTION')->get('DB')->getFullConfigAsArray(), true) . PHP_EOL . PHP_EOL;
		echo '$iniConfig->get("PRODUCTION")->get("DB")->get("user"): '
		    . var_export($iniConfig->get('PRODUCTION')->get('DB')->get('user'), true) . PHP_EOL . PHP_EOL;

		echo "################ LOADER FOR INI WITHOUT CACHE #######################\n";
		$loader = new ConfigLoader(__DIR__ . "/resources/test.ini",
			new Mcustiel\Config\Drivers\Reader\ini\Reader()
		);
		$init = microtime(true);
		for ($i = 0; $i < 50000; $i++) {
		    $config = $loader->load();
		}
		echo "Time = " . (microtime(true) - $init) . PHP_EOL;
		var_export($config->getFullConfigAsArray());
		echo PHP_EOL . PHP_EOL;

		echo "############## LOADER FOR INI WITH PHP FILE CACHE ####################\n";
		$cacherConfig = new \stdClass();
		$cacherConfig->path = __DIR__ . "/resources/";
		$loader = new ConfigLoader(__DIR__ . "/resources/test.ini",
			new Mcustiel\Config\Drivers\Reader\ini\Reader(),
			new Mcustiel\Config\Drivers\Cacher\file\php\Cacher($cacherConfig)
		);
		$init = microtime(true);
		for ($i = 0; $i < 50000; $i++) {
		    $config = $loader->load();
		}
		echo "Time = " . (microtime(true) - $init) . PHP_EOL;
		var_export($config->getFullConfigAsArray());
		echo PHP_EOL . PHP_EOL;

		echo "############ LOADER FOR INI WITH PHP SERIALIZED CACHE ################\n";
		$cacherConfig = new \stdClass();
		$cacherConfig->path = __DIR__ . "/resources/";
		$loader = new ConfigLoader(__DIR__ . "/resources/test.ini",
			new Mcustiel\Config\Drivers\Reader\ini\Reader(),
			new Mcustiel\Config\Drivers\Cacher\file\php\Cacher($cacherConfig)
		);
		$init = microtime(true);
		for ($i = 0; $i < 50000; $i++) {
		    $config = $loader->load();
		}
		echo "Time = " . (microtime(true) - $init) . PHP_EOL;
		var_export($config->getFullConfigAsArray());
		echo PHP_EOL . PHP_EOL;

		echo "############ LOADER FOR INI WITH MEMCACHE CACHE ################\n";
		$cacherConfig = new \stdClass();
		$cacherConfig->path = __DIR__ . "/resources/";
		$loader = new ConfigLoader(__DIR__ . "/resources/test.ini",
		    new Mcustiel\Config\Drivers\Reader\ini\Reader(),
		    new Mcustiel\Config\Drivers\Cacher\memcache\Cacher($cacherConfig)
		);
		$init = microtime(true);
		for ($i = 0; $i < 50000; $i++) {
            $config = $loader->load();
		}
		echo "Time = " . (microtime(true) - $init) . PHP_EOL;
		var_export($config->getFullConfigAsArray());
		echo PHP_EOL . PHP_EOL;
    }
}
