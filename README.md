## Nette service generator

```php
<?php declare(strict_types = 1);

use Doctrine\Common\EventSubscriber;
use WebChemistry\ServiceAttribute\Generator\NeonFile;
use WebChemistry\ServiceAttribute\Generator\ServiceNeonGenerator;
use WebChemistry\ServiceAttribute\Group\ClassStartsWithGroup;
use WebChemistry\ServiceAttribute\Group\DeprecatedGroup;
use WebChemistry\ServiceAttribute\Group\InstanceOfGroup;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Sort\ServiceSorter;

require __DIR__ . '/vendor/autoload.php';

$directory = Finder::find('*.php')
	->from(__DIR__ . '/app');

$services = ServiceFinder::findServices($directory);

// grouping
(new DeprecatedGroup())
	->group($services);

(new ClassStartsWithGroup())
	->addMapping('cron tasks', 'Tasks\\')
	->group($services);

(new InstanceOfGroup())
	->addMapping(EventSubscriber::class, 'doctrine event subscribers')
	->group($services);

// sorting
$services = ServiceSorter::sort($services);


$neon = new NeonFile($path = __DIR__ . '/generated/services.neon', $services);

echo $neon->diff();
echo sprintf("File generated from %d services: file://%s\n", count($services), $path);

$neon->save();

echo (new ServiceNeonGenerator($services))->generate();
```

run:
```bash
php services.php
```
