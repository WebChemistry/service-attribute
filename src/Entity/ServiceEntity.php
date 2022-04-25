<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use Exception;
use LogicException;
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

		if ($method = $this->attribute->serviceFromMethod) {
			if (!$this->reflection->hasMethod($method)) {
				throw new LogicException(
					sprintf('Class %s does not have method %s.', $this->reflection->getName(), $method)
				);
			}
			$values['autowired'] = false;

			if (!$this->name) {
				$this->name = sprintf('_serviceFromMethod.%d', self::$counter++);
			}

			$services[] = sprintf('@%s::%s', $this->name, $method);
		}

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

		return $services;
	}

	private function prolog(array &$values, array $services): array
	{
		if ($method = $this->attribute->serviceFromMethod) {
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
