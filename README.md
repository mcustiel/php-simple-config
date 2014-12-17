php-simple-config
=================

What is it?
-----------

php-simple-config is a simple and extensible component that allows the developer to abstract the application configuration management. 

php-simple-config uses a minimalistic approach and supports different types of configuration files, the currently supported types are:
* PHP files (containing a config array).
* INI files
* JSON files
* YAML files

The component can read and write these configuration formats.

Also, it allows the developer to cache the configurations to increase the performance when accessing to it. Currently just one configuration cacher is supported: **PHP file**. This cacher saves the parsed configuration in a .php file as a PHP array.

Installation
------------

### Composer:
Just add the packagist dependecy: 
```javascript  
"require": {
	// ...
        "mcustiel/php-simple-config": ">=1.2.0"
    }	
```

Or, if you want to get it directly from github, adding this to your composer.json should be enough:
```javascript  
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mcustiel/php-simple-config"
        }
    ],
    "require": {
    	// ...
        "mcustiel/php-simple-config": "dev-master"
    }
}
```
Or just download the code. :D

How to use it?
--------------

###Reading configuration
First you have to create a configuration file, in this example it is a PHP file. If the format is PHP you must define a variable containing an array with the configuration and return it, for JSON or INI you don't need any special convention:
```php
<?php 
return array(
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
or, if you prefer it:
```php
<?php 
$config['PRODUCTION']['DB']['user'] = 'root';
$config['PRODUCTION']['DB']['pass'] = 'root';
$config['PRODUCTION']['DB']['host'] = 'localhost';
// ...
return $config;
```
Then you can access the config from your code using a Reader object:
```php
$reader = new Mcustiel\Config\Drivers\Reader\php\Reader();
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

####Caching the config
php-simple-config allows the developer to create a cached version of the configuration to open and parse it faster. To do this you must provide the ConfigLoader with a Cacher object as shown in following code block:

```PHP
use Mcustiel\Config\Drivers\Reader\ini\Reader as IniReader;
use Mcustiel\Config\Cacher;

$loader = new ConfigLoader("/test.ini",
    new IniReader(),
    new Cacher('/path/to/cache/dir/', 'test.ini.cache')
);
// If the file is already cached, then next sentence loads it from cache; otherwise it's loaded
// from original config file and then saved in the cached version.
$config = $loader->load();
```

###Writing configuration
To write the configuration to a file you need a Writer object: 
```php
$writer = new Mcustiel\Config\Drivers\Writer\ini\Writer($iniConfig);
$writer->write(__DIR__ . "/resources/test-written.ini");
```
> *NOTE*
> When writing using ini or yaml format, the library does not guarantee that 
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

[TEST]
DB.user = root
DB.pass = root
DB.host = localhost
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
In the unit and functional tests you can see examples of php-simple-config use.
