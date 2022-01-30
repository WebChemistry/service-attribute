## Nette service generator

```php
<?php declare(strict_types = 1);

use Nette\Utils\Finder;
use WebChemistry\ServiceAttribute\DecoratorFinder;
use WebChemistry\ServiceAttribute\Generator\DecoratorNeonGenerator;
use WebChemistry\ServiceAttribute\Generator\NeonFile;
use WebChemistry\ServiceAttribute\Generator\ServiceNeonGenerator;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Validator\DecoratorValidator;

require __DIR__ . '/vendor/autoload.php';

$directory = Finder::find('*.php')
	->from(__DIR__ . '/app');

// decorators
echo "\e[36mDecorators\e[39m\n";

$decorators = DecoratorFinder::findDecorators($directory);

DecoratorValidator::validate($decorators);

$neon = new NeonFile($path = __DIR__ . '/app/generated/decorators.neon', (new DecoratorNeonGenerator($decorators))->generate());
$neon->diff();
$neon->save();

echo sprintf("File generated from %d decorators: file://%s\n", count($decorators), $path);

// services
echo "\e[36mServices\e[39m\n";
$services = ServiceFinder::findServices($directory);

$neon = new NeonFile($path = __DIR__ . '/app/generated/services.neon', (new ServiceNeonGenerator($services))->generate());
$neon->diff();
$neon->save();

echo sprintf("File generated from %d services: file://%s\n", count($services), $path);
```

run:
```bash
php services.php
```
