<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Validator;

use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;

final class DecoratorValidator
{

	/**
	 * @param DecoratorEntity[] $decorators
	 */
	public static function validate(array $decorators): void
	{
		foreach ($decorators as $decorator) {
			$reflection = $decorator->reflection;

			foreach ($decorator->attribute->setup as $method) {
				if (!$reflection->hasMethod($method)) {
					echo sprintf("Warning: decorator %s have not method %s\n", $reflection->getName(), $method);
					continue;
				}

				$method = $reflection->getMethod($method);
				if (!$method->isFinal()) {
					echo sprintf(
						"Warning: decorator method %s::%s is not final\n",
						$reflection->getName(),
						$method->getName()
					);
				}
			}
		}
	}

}
