<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use _HumbugBox39a196d4601e\Nette\Neon\Exception;
use Nette\Neon\Neon;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;
use WebChemistry\ServiceAttribute\Neon\NeonPrettyEncoder;

final class DecoratorNeonGenerator
{

	/**
	 * @param DecoratorEntity[] $decorators
	 */
	public function __construct(
		private array $decorators
	)
	{
	}

	public function generate(): string
	{
		$decorators = [];
		foreach ($this->decorators as $decorator) {
			$decorators = $decorator->toArray($decorators);
		}

		return NeonPrettyEncoder::encode(['decorator' => $decorators]);
	}

}
