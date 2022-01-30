<?php declare(strict_types = 1);

namespace Tests;

use WebChemistry\ServiceAttribute\Attribute\Decorator;

#[Decorator(setup: ['setup'])]
class Decorator1
{

	public function setup(): void
	{

	}

}
