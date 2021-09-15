<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Service
{

	public function __construct(
		public ?string $name = null,
		public ?array $args = null,
		public ?array $tags = null,
		public string|bool|null $serviceFromMethod = null,
		public int $priority = 0,
	)
	{
	}

}
