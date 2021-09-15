<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use Exception;
use Nette\Neon\Neon;
use Nette\Utils\Arrays;
use ReflectionClass;
use WebChemistry\ServiceAttribute\Attribute\Service;
use WebChemistry\ServiceAttribute\Neon\NeonComment;

final class ServiceEntity
{

	public static int $counter = 0;

	public string $className;

	public ?string $name;

	public function __construct(
		public ReflectionClass $reflection,
		public Service $attribute,
		public bool $ignore = false,
	)
	{
		$this->className = $this->reflection->getName();
		$this->name = $this->attribute->name;
	}

	public function toArray(array $services): array
	{
		$values = array_filter([
			'factory' => $this->className,
			'arguments' => $this->attribute->args,
			'tags' => $this->attribute->tags,
		]);

		$services = $this->prolog($values, $services);

		// factory -> implement, if needed
		if (count($values) > 1 && isset($values['factory']) && $this->reflection->isInterface()) {
			$values['implement'] = $values['factory'];

			unset($values['factory']);
		}

		// inline factory
		if (count($values) === 1 && isset($values['factory'])) {
			$entity = $values['factory'];
		} else {
			$entity = $values;
		}

		if ($this->name) {
			$services[$this->name] = $entity;
		} else {
			$services[] = $entity;
		}

		return $this->epilog($values, $services);
	}

	private function prolog(array &$values, array $services): array
	{
		if ($method = $this->attribute->serviceFromMethod) {
			$services[] = new NeonComment('Starts service from method');

			if (!is_string($method)) {
				$methods = $this->reflection->getMethods();
				if (count($methods) !== 1) {
					throw new Exception(
						sprintf('Class %s must have exactly one method.', $this->reflection->getName())
					);
				}

				$method = $methods[array_key_first($methods)]->getName();
			}

			$services = $this->generateServiceFactoryMethod($values, $services, $method);
		}

		return $services;
	}

	private function epilog(array &$values, array $services): array
	{
		if ($this->attribute->serviceFromMethod) {
			$services[] = new NeonComment('Ends service from method');
		}

		return $services;
	}

	private function generateServiceFactoryMethod(array &$service, array $array, string $method): array
	{
		$service['autowired'] = false;

		if (!$this->name) {
			$this->name = sprintf('generated.factoryMethod.%d', self::$counter++);
		}

		$array[] = sprintf('@%s::%s', $this->name, $method);

		return $array;
	}

}
