<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use ReflectionClass;
use WebChemistry\ServiceAttribute\Attribute\Service;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

class ServiceFinder
{

	public static function findServices(string $directory): ServiceEntityCollection
	{
		$collection = new ServiceEntityCollection();
		foreach (ClassFinder::findClasses($directory) as $class) {
			$reflection = new ReflectionClass($class);
			$attributes = $reflection->getAttributes(Service::class);

			if (!$attributes) {
				continue;
			}

			$collection->addEntity(
				new ServiceEntity(
					$reflection,
					$reflection->getName(),
					$attributes[0]->getArguments()['name'] ?? null,
					$attributes[0]->getArguments()['args'] ?? null
				)
			);
		}

		return $collection;
	}

}
