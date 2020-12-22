<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

class ServiceGroup implements ServiceEntityInterface
{

	/**
	 * @param ServiceEntity[] $entities
	 */
	public function __construct(
		private string $comment,
		private array $entities = [],
	)
	{
	}

	public function generate(): string
	{
		return "# " . $this->comment;
	}

	public function addEntity(ServiceEntity $entity): void
	{
		$this->entities[] = $entity;
	}

	public function getComment(): string
	{
		return $this->comment;
	}

	/**
	 * @return ServiceEntity[]
	 */
	public function getEntities(): array
	{
		return $this->entities;
	}

}
