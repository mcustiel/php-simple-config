php-simple-config
=================

What is it?
-----------

php-simple-config is a simple and extensible component that allows the developer to abstract the application configuration management. 

php-simple-config uses a minimalistic approach and supports different types of configuration files, the currently supported types are:
* PHP files (containing a config array).
* INI files
* JSON files

The component can read and write these configuration formats.

Also, it allows the developer to cache the configurations to increase the performance when accessing to it. This functionality is experimental (currently testing the increment in performance) but can be used, the types of cache supported are:
* PHP file
* serialized file
* memcached

Installation
------------

### Composer:
Adding this to your composer.json should be enough (not tested, sorry):
```javascript  
{
    "name": "mcustiel/php-simple-config",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mcustiel/php-simple-config"
        }
    ],
    "require": {
        "mcustiel/php-simple-config": "dev-master"
    }
}
```

How to use it?
--------------

###Reading configuration

First you have to create a configuration file, in this example it is a PHP file. If the format is PHP you must define a variable named $config containing an array with the configuration, for JSON or INI you don't need any special convention:
```php
<?php 
$config = array(
	'PRODUCTION' => array(
	    'DB' => array(
	        'user' => 'root',
	        'pass' => 'root',
	        'host' => 'localhost'
	    )
	),
	'STAGE' => array(
	    'DB' => array(
	        'user' => 'root',
	        'pass' => 'root',
	        'host' => 'localhost'
	    )
	),
	'LOCAL' => array(
	    'DB' => array(
	        'user' => 'root',
	        'pass' => 'root',
	        'host' => 'localhost'
	    )
	),
);
```

Then you can access the config from your code using a Reader object:
```php
$reader = new \mcustiel\config\drivers\reader\php\Reader();
$reader->read(__DIR__ . "/resources/test.php");
$config = $reader->getConfig();
```

###Accessing configuration
The config object allows you to access the information in the configuration file:
```php
$config->getFullConfigAsArray(); // This will return the full configuration as an array.
$config->getFullConfigAsObject(); // This will return the full configuration as a \stdClass object.
$config->get('PRODUCTION'); // Will return a $config object to access the subkeys defined under "PRODUCTION"
$config->get('PRODUCTION')->get('DB')->get('user'); // Will return 'root'
```

###Writing configuration
To write the configuration to a file you need a Writer object: 
```php
$writer = new \mcustiel\config\drivers\writer\ini\Writer($iniConfig);
$writer->write(__DIR__ . "/resources/test-written.ini");
```
> *NOTE*
> When writing using ini format, the library does not guarantee that 
> the original format and item order is kept. But the file is still
> parseable

*Example about the note*

The original ini file 
```ini
b = notAnArray
c = alsoNotAnArray
a.property = value
a.property.deeper = deeperValue

[PRODUCTION]
DB.user = root
DB.pass = root
DB.host = localhost
a.property.inside.production = test

[STAGE]
DB.user = root
DB.pass = root
DB.host = localhost

[LOCAL]
DB.user = root
DB.pass = root
DB.host = localhost

[TEST:DB]
user = root
pass = root
host = localhost
``` 

Could be transformed to:
```ini
b = notAnArray
c = alsoNotAnArray

[PRODUCTION]
DB.user = root
DB.pass = root
DB.host = localhost
a.property.inside.production = test

[STAGE]
DB.user = root
DB.pass = root
DB.host = localhost

[LOCAL]
DB.user = root
DB.pass = root
DB.host = localhost

[a]
property = value
property.deeper = deeperValue

[TEST]
DB.user = root
DB.pass = root
DB.host = localhost
``` 

###Examples:
There's not unit tests yet but you cant see the test/testrun.php script to see this project working.
