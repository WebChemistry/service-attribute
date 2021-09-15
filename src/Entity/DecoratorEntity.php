<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use ReflectionClass;
use WebChemistry\ServiceAttribute\Attribute\Decorator;

final class DecoratorEntity
{

	public string $className;

	public function __construct(
		public ReflectionClass $reflection,
		public Decorator $attribute,
	)
	{
		$this->className = $this->reflection->getName();
	}

	public function toArray(array $decorators): array
	{
		$decorators[$this->className] = array_filter([
			'setup' => $this->attribute->setup,
			'tags' => $this->attribute->tags,
		]);

		return $decorators;
	}

}
