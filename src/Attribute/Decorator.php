<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Decorator
{

	public function __construct(
		public array $setup = [],
		public array $tags = [],
	)
	{
	}

}
