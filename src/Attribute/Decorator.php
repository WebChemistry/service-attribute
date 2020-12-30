<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Decorator
{

	public function __construct(
		public array $setup = []
	)
	{
	}

}
