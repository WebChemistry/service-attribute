<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use Nette\Utils\Finder;
use ReflectionClass;
use WebChemistry\ClassFinder\ClassFinder;
use WebChemistry\ServiceAttribute\Attribute\Decorator;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;

final class DecoratorFinder
{

	/**
	 * @return DecoratorEntity[]
	 */
	public static function findDecorators(Finder $directory): array
	{
		$decorators = [];

		foreach (ClassFinder::findClasses($directory) as $class) {
			$reflection = new ReflectionClass($class);
			$attributes = $reflection->getAttributes(Decorator::class);

			if (!$attributes) {
				continue;
			}

			/** @var Decorator $attribute */
			$attribute = $attributes[0]->newInstance();
			$decorators[$class] = new DecoratorEntity($reflection, $attribute);
		}

		ksort($decorators);

		return array_values($decorators);
	}

}
