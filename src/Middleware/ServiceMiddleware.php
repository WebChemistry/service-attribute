<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Middleware;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

interface ServiceMiddleware
{

	public function process(ServiceEntity $service, array $struct): array;

}
