<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use Nette\Neon\Neon;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;

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

		return $decorators ? Neon::encode(['decorator' => $decorators], true) : '';
	}

}
