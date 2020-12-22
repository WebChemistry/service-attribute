<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Group;

use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;
use WebChemistry\ServiceAttribute\Entity\ServiceGroup;

final class InstanceOfGroup implements GrouperInterface
{

	/** @var string[] */
	private array $mapping = [];

	public function addMapping(string $instanceOf, string $group): self
	{
		$this->mapping[$group] = $instanceOf;

		return $this;
	}

	public function group(ServiceEntityCollection $collection): void
	{
		$entities = [];
		foreach ($this->mapping as $comment => $instanceOf) {
			foreach ($collection->getEntities() as $index => $entity) {
				if (is_a($entity->className, $instanceOf, true)) {
					$entities[$index] = $entity;
				}
			}

			$collection->createGroupOrAppend($entities, $comment);
		}
	}


}
