<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Service
{

	public function __construct(
		public ?string $name = null,
		public ?string $args = null,
		public ?array $tags = null,
	)
	{
	}

}
