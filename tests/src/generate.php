<?php declare(strict_types = 1);

use Tester\Assert;
use WebChemistry\ServiceAttribute\Generator\ServiceNeonGenerator;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Sort\ServiceSorter;

require __DIR__ . '/../bootstrap.php';

$generator = new ServiceNeonGenerator(
	ServiceSorter::sort(ServiceFinder::findServices(__DIR__ . '/../files'))
);

Assert::same(
	"services:
\t- Class3
\tclass2: Tests\Class2
\t- Tests\Interface2
\t- Tests\Tester\Class1(%arg%)
\t- Tests\Tester\Interface1
",
	$generator->generate()
);
