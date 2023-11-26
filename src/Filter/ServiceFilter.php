<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Filter;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

final class ServiceFilter
{

	/**
	 * @param ServiceEntity[] $services
	 */
	public function __construct(
		private array $services,
	)
	{
	}

	public function requireCategory(string $category): self
	{
		foreach ($this->services as $i => $service) {
			if ($service->attribute->category !== $category) {
				unset($this->services[$i]);
			}
		}

		return $this;
	}

	/**
	 * @return list<ServiceEntity>
	 */
	public function all(): array
	{
		return array_values($this->services);
	}

	/**
	 * @param string[] $categories
	 */
	public function requireCategories(array $categories): self
	{
		foreach ($this->services as $i => $service) {
			if (!in_array($service->attribute->category, $categories, true)) {
				unset($this->services[$i]);
			}
		}

		return $this;
	}

}
