## Nette service generator

```php
<?php declare(strict_types = 1);

use Doctrine\Common\EventSubscriber;
use WebChemistry\ServiceAttribute\Generator\ServiceNeonGenerator;
use WebChemistry\ServiceAttribute\Group\ClassStartsWithGroup;
use WebChemistry\ServiceAttribute\Group\DeprecatedGroup;
use WebChemistry\ServiceAttribute\Group\InstanceOfGroup;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Sort\ServiceSorter;

require __DIR__ . '/vendor/autoload.php';

$services = ServiceFinder::findServices(__DIR__ . '/../app');

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

echo (new ServiceNeonGenerator($services))->generate();
```

run:
```bash
php services.php > app/generated/services.neon
```
