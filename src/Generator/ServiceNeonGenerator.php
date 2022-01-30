<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use Nette\Neon\Neon;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

final class ServiceNeonGenerator
{

	private string $cache;

	/**
	 * @param ServiceEntity[] $collection
	 */
	public function __construct(
		private array $collection
	)
	{
	}

	public function generate(): string
	{
		if (!isset($this->cache)) {
			$services = [];

			foreach ($this->collection as $service) {
				if ($service->ignore) {
					continue;
				}

				$services = $service->toArray($services);
			}

			$this->cache = $services ? Neon::encode([
				'services' => $services,
			], true) : '';
		}

		return $this->cache;
	}

}
