<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Sort;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;
use WebChemistry\ServiceAttribute\Entity\ServiceGroup;

final class ServiceSorter
{

	/**
	 * @param ServiceEntity[] $services
	 * @return ServiceEntity[]
	 */
	public static function sort(ServiceEntityCollection $collection): ServiceEntityCollection
	{
		$entities = self::sortServices($collection->getEntities());

		$groups = [];
		foreach ($collection->getGroups() as $group) {
			$groups[] = new ServiceGroup($group->getComment(), self::sortServices($group->getEntities()));
		}

		return new ServiceEntityCollection($entities, $groups);
	}

	/**
	 * @param ServiceEntity[] $services
	 * @return ServiceEntity[]
	 */
	private static function sortServices(array $services): array
	{
		usort(
			$services,
			function (ServiceEntity $entity, ServiceEntity $entity1): int
			{
				if ($entity->attribute->priority === $entity1->attribute->priority) {
					return strcmp($entity->className, $entity1->className);
				}

				return $entity1->attribute->priority <=> $entity->attribute->priority;
			}
		);

		return $services;
	}

}
