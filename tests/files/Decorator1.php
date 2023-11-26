<?php declare(strict_types = 1);

namespace Tests;

use WebChemistry\ServiceAttribute\Attribute\Service;

#[Service(calls: ['setup'])]
abstract class Decorator1
{

	public function setup(): void
	{

	}

}
