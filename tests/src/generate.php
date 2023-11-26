<?php declare(strict_types = 1);

use Nette\Utils\Finder;
use Tester\Assert;
use WebChemistry\ServiceAttribute\Bridge\Nette\NetteServiceGenerator;
use WebChemistry\ServiceAttribute\ServiceFinder;

require __DIR__ . '/../bootstrap.php';

$neon = NetteServiceGenerator::generate(
	ServiceFinder::findServices(Finder::findFiles('*.php')->from(__DIR__ . '/../files')),
);

Assert::same(
	'services:
        - Class3
        - Tests\Tester\Interface1
        -
                factory: Tests\Decorator1
                setup:
                        - setup

        class2: Tests\Class2
        - Class4
        - Tests\Interface2
        -
                factory: Tests\Tester\Class1
                arguments:
                        - %arg%',
	$neon
);
