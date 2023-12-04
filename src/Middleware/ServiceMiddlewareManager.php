<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Middleware;

final class ServiceMiddlewareManager
{

	/** @var mixed[] */
	private array $structure = [];

	/**
	 * @return mixed[]
	 */
	public function &getStructure(): array
	{
		return $this->structure;
	}

}
