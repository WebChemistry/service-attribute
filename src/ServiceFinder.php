<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use Nette\Utils\Finder;
use ReflectionClass;
use WebChemistry\ServiceAttribute\Attribute\Ignore;
use WebChemistry\ServiceAttribute\Attribute\Service;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

class ServiceFinder
{

	public static function findServices(Finder $directory): ServiceEntityCollection
	{
		$collection = new ServiceEntityCollection();
		foreach (ClassFinder::findClasses($directory) as $class) {
			$reflection = new ReflectionClass($class);
			$attributes = $reflection->getAttributes(Service::class);
			$ignore = (bool) $reflection->getAttributes(Ignore::class);

			if (!$attributes) {
				continue;
			}

			/** @var Service $attribute */
			$attribute = $attributes[0]->newInstance();

			$collection->addEntity(
				new ServiceEntity(
					$reflection,
					$attribute,
					$ignore,
				)
			);
		}

		return $collection;
	}

}
