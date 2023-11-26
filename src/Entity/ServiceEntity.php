<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use ReflectionClass;
use WebChemistry\ServiceAttribute\Attribute\Service;

final class ServiceEntity
{

	public static int $counter = 0;

	public string $className;

	public ?string $name;

	public function __construct(
		public ReflectionClass $reflection,
		public Service $attribute,
	)
	{
		$this->className = $this->reflection->getName();
		$this->name = $this->attribute->name;
	}

}
