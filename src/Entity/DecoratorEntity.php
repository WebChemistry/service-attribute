<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use ReflectionClass;

final class DecoratorEntity
{

	public function __construct(
		public ReflectionClass $reflection,
		public string $className,
		public array $setup = [],
	)
	{
	}

}
