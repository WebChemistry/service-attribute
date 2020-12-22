<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use Nette\Neon\Entity;
use Nette\Neon\Neon;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

final class NeonFile
{

	public function __construct(
		private string $file,
		private ServiceEntityCollection $collection
	)
	{
	}

	public function diff(): string
	{
		if (!file_exists($this->file)) {
			return "\e[34mNew file generated!\e[39m\n";
		}

		$services = Neon::decode(file_get_contents($this->file))['services'] ?? [];
		$mapping = [];
		foreach ($services as $service) {
			if ($service instanceof Entity) {
				$mapping[$service->value] = true;
			} else {
				$mapping[$service] = true;
			}
		}

		$added = [];
		$entities = [...$this->collection->getEntities()];
		foreach ($this->collection->getGroups() as $group) {
			$entities = array_merge($entities, $group->getEntities());
		}

		foreach ($entities as $entity) {
			if (isset($mapping[$entity->className])) {
				unset($mapping[$entity->className]);

				continue;
			} else {
				$added[] = $entity->className;
			}
		}

		if (!$mapping && !$added) {
			return "\e[34mNo changes!\e[39m\n";
		}

		$diff = '';
		foreach ($mapping as $class => $_) {
			$diff .= "\e[31m- " . $class . "\e[39m\n";
		}

		foreach ($added as $class) {
			$diff .= "\e[32m+ " . $class . "\e[39m\n";
		}

		return $diff;
	}

	public function save(): void
	{
		file_put_contents($this->file, (new ServiceNeonGenerator($this->collection))->generate());
	}

}
