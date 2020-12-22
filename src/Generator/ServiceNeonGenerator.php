<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

final class ServiceNeonGenerator
{

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
		$neon = "services:\n";

		foreach ($this->collection->getEntities() as $service) {
			$neon .= $this->generateService($service);
		}

		foreach ($this->collection->getGroups() as $group) {
			$neon .= "\n\t";
			$neon .= $group->generate();
			$neon .= "\n";
			foreach ($group->getEntities() as $service) {
				$neon .= $this->generateService($service);
			}
		}

		return $neon;
	}

	private function generateService(ServiceEntity $service): string
	{
		return
			"\t" .
			$service->generate() .
			"\n";
	}

}
