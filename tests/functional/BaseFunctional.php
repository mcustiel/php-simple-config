<?php
namespace Functional;

use Mcustiel\Config\Config;
use Mcustiel\Config\Drivers\Reader\php\Reader as PhpReader;
use Mcustiel\Config\Drivers\Reader\ini\Reader as IniReader;
use Mcustiel\Config\Drivers\Reader\json\Reader as JsonReader;
use Mcustiel\Config\Drivers\Reader\yaml\Reader as YamlReader;

abstract class BaseFunctional extends \PHPUnit_Framework_TestCase
{
    protected function checkGeneratedConfigIsCorrect(Config $config)
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

    protected function loadJsonConfig($file)
    {
        $reader = new JsonReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }

    protected function loadYamlConfig($file)
    {
        $reader = new YamlReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }

    protected function loadPhpConfig($file)
    {
        $reader = new PhpReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }

    protected function loadIniConfig($file)
    {
        $reader = new IniReader();
        $reader->read(FIXTURES_PATH . $file);
        $config = $reader->getConfig();

        return $config;
    }
}
