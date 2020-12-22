<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Group;

use JetBrains\PhpStorm\Deprecated;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;
use WebChemistry\ServiceAttribute\Entity\ServiceGroup;

final class DeprecatedGroup implements GrouperInterface
{

	public function group(ServiceEntityCollection $collection): void
	{
		$group = new ServiceGroup('deprecated');

		foreach ($collection->getEntities() as $index => $entity) {
			if ($entity->reflection->getAttributes(Deprecated::class)) {
				$group->addEntity($entity);
				$collection->removeEntityByIndex($index);
			} else {
				$entities[] = $entity;
			}
		}

		if ($group->getEntities()) {
			$collection->addGroup($group);
		}
	}

}
