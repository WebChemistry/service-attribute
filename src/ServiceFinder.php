<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use Nette\Utils\Finder;
use ReflectionClass;
use WebChemistry\ClassFinder\ClassFinder;
use WebChemistry\ServiceAttribute\Attribute\Ignore;
use WebChemistry\ServiceAttribute\Attribute\Service;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

class ServiceFinder
{

	/**
	 * @return ServiceEntity[]
	 */
	public static function findServices(Finder $directory, ?string $environment = null): array
	{
		$collection = [];

		foreach (ClassFinder::findClasses($directory) as $class) {
			$reflection = new ReflectionClass($class);
			$attributes = $reflection->getAttributes(Service::class);
			$ignore = (bool) $reflection->getAttributes(Ignore::class);

			if (!$attributes) {
				continue;
			}

			/** @var Service $attribute */
			$attribute = $attributes[0]->newInstance();

			if ($attribute->environment && $attribute->environment !== $environment) {
				continue;
			}

			$collection[] = new ServiceEntity($reflection, $attribute, $ignore);
		}

		return $collection;
	}

}
