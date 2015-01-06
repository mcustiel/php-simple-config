<?php
namespace Functional;

class ReaderTest extends BaseFunctional
{
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
}
