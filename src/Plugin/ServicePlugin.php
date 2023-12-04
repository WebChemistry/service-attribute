<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Plugin;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

interface ServicePlugin
{

	/**
	 * @param mixed[] $schema
	 * @return mixed[]|null
	 */
	public function process(array $schema, ServiceEntity $service): ?array;

}
