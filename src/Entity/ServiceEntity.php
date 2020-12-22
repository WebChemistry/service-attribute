<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use ReflectionClass;

final class ServiceEntity implements ServiceEntityInterface
{

	public function __construct(
		public ReflectionClass $reflection,
		public string $className,
		public ?string $name,
		public ?string $args,
	)
	{
	}

	public function generate(): string
	{
		return $this->generateIndex() . $this->className . $this->generateArgs();
	}

	private function generateIndex(): string
	{
		if ($this->name) {
			return $this->name . ': ';
		} else {
			return '- ';
		}
	}

	private function generateArgs(): string
	{
		if ($this->args) {
			return '(' . $this->args . ')';
		}

		return '';
	}

}
