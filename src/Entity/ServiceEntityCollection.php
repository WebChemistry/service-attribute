<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use Countable;

final class ServiceEntityCollection implements Countable
{

	/**
	 * @param ServiceEntity[] $entities
	 * @param ServiceGroup[] $groups
	 */
	public function __construct(
		private array $entities = [],
		private array $groups = [],
	)
	{
	}

	public function count(): int
	{
		$count = count($this->entities);
		foreach ($this->groups as $group) {
			$count += count($group);
		}

		return $count;
	}

	public function addEntity(ServiceEntity $entity): void
	{
		$this->entities[] = $entity;
	}

	public function addGroup(ServiceGroup $group): void
	{
		$this->groups[$group->getComment()] = $group;
	}

	public function removeEntityByIndex(int $index): void
	{
		unset($this->entities[$index]);
	}

	/**
	 * @return ServiceEntity[]
	 */
	public function getEntities(): array
	{
		return $this->entities;
	}

	/**
	 * @return ServiceGroup[]
	 */
	public function getGroups(): array
	{
		return $this->groups;
	}

	/**
	 * @param ServiceEntity[] $entities
	 */
	public function createGroupOrAppend(array $entities, string $comment): void
	{
		if (!$entities) {
			return;
		}

		$group = $this->groups[$comment] ??= new ServiceGroup($comment);

		foreach ($entities as $index => $entity) {
			$group->addEntity($entity);

			unset($this->entities[$index]);
		}
	}

}
