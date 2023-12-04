<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Output\Output;
use WebChemistry\ServiceAttribute\Plugin\ServicePlugin;

final class ServiceGenerator
{

	/**
	 * @param ServicePlugin[] $plugins
	 */
	public function __construct(
		private array $plugins,
	)
	{
	}

	/**
	 * @param ServiceEntity[] $services
	 */
	public function generate(array $services, Output $output): string
	{
		$schema = [];

		foreach ($services as $service) {
			foreach ($this->plugins as $plugin) {
				$result = $plugin->process($schema, $service);

				if ($result !== null) {
					$schema = $result;

					break;
				}
			}
		}

		return $output->toString($schema);
	}

}
