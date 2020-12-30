<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Generator;

use _HumbugBox39a196d4601e\Nette\Neon\Exception;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;

class DecoratorNeonGenerator
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
		$neon = "decorator:\n";

		foreach ($this->decorators as $decorator) {
			if (!$decorator->setup) {
				throw new Exception(sprintf('Setup is missing for decorator %s', $decorator->className));
			}

			$neon .= sprintf("\t%s:\n", $decorator->className);
			$neon .= "\t\tsetup:\n";
			foreach ($decorator->setup as $setup) {
				$neon .= sprintf("\t\t\t- %s\n", $setup);
			}
		}

		return $neon;
	}

}
