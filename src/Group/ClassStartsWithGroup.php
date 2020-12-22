<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Group;

use JetBrains\PhpStorm\ArrayShape;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

final class ClassStartsWithGroup implements GrouperInterface
{

	#[ArrayShape(['string', 'string'])]
	private array $mapping = [];

	public function addMapping(string $comment, string $startsWith): self
	{
		$this->mapping[] = [$comment, $startsWith];

		return $this;
	}

	public function group(ServiceEntityCollection $collection): void
	{
		foreach ($this->mapping as [$comment, $startsWith]) {
			$entities = [];
			foreach ($collection->getEntities() as $index => $entity) {
				if (str_starts_with($entity->className, $startsWith)) {
					$entities[$index] = $entity;
				}
			}

			$collection->createGroupOrAppend($entities, $comment);
		}
	}

}
