<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use ReflectionClass;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

final class DecoratorFinder
{

	/**
	 * @return DecoratorEntity[]
	 */
	public static function findDecorators(string $directory): array
	{
		$decorators = [];
		foreach (ClassFinder::findClasses($directory) as $class) {
			$reflection = new ReflectionClass($class);
			$attributes = $reflection->getAttributes(Decorator::class);

			if (!$attributes) {
				continue;
			}

			$args = $attributes[0]->getArguments();
			$decorators[$class] = new DecoratorEntity(
				$reflection,
				$class,
				$args['setup'] ?? $args[0] ?? []
			);
		}

		ksort($decorators);

		return array_values($decorators);
	}

}
