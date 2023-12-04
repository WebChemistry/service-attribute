<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Output;

interface Output
{

	/**
	 * @param mixed[] $schema
	 */
	public function toString(array $schema): ?string;

}
