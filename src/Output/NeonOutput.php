<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Output;

use Nette\Neon\Neon;

final class NeonOutput implements Output
{

	/**
	 * @param mixed[] $schema
	 */
	public function toString(array $schema): string
	{
		return $schema ? Neon::encode($schema, true) : '';
	}

}
