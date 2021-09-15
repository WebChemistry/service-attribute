<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use WeakMap;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;
use WebChemistry\ServiceAttribute\Neon\NeonComment;
use WebChemistry\ServiceAttribute\Neon\NeonPrettyEncoder;

final class ServiceNeonGenerator
{

	private string $cache;

	/**
	 * @param ServiceEntityCollection[] $collection
	 */
	public function __construct(
		private ServiceEntityCollection $collection
	)
	{
	}

	public function generate(): string
	{
		if (!isset($this->cache)) {
			$services = $this->generateFromEntities([], $this->collection->getEntities());

			foreach ($this->collection->getGroups() as $group) {
				$services[] = new NeonComment($group->getComment());

				$services = $this->generateFromEntities($services, $group->getEntities());
			}

			$this->cache = NeonPrettyEncoder::encode([
				'services' => $services,
			]);
		}

		return $this->cache;
	}

	/**
	 * @param mixed[] $services
	 * @param ServiceEntity[] $entities
	 * @return mixed[]
	 */
	private function generateFromEntities(array $services, array $entities): array
	{
		foreach ($entities as $service) {
			if ($service->ignore) {
				continue;
			}

			$services = $service->toArray($services);
		}

		return $services;
	}

}
