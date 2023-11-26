<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Service
{

	/**
	 * @param mixed[]|null $args
	 * @param mixed[]|null $calls
	 * @param array<string, mixed> $options
	 * @param mixed[] $tags
	 */
	public function __construct(
		public ?string $name = null,
		public ?array $args = null,
		public ?array $calls = null,
		public string $category = '',
		public bool $skip = false,
		public array $tags = [],
		public array $options = [],
	)
	{
	}

}
