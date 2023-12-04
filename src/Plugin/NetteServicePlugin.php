<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Plugin;

use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

final class NetteServicePlugin implements ServicePlugin
{

	/**
	 * @param mixed[] $schema
	 * @return mixed[]|null
	 */
	public function process(array $schema, ServiceEntity $service): ?array
	{
		$values = array_filter([
			'factory' => $service->className,
			'arguments' => $service->attribute->args,
		]);

		if ($service->attribute->tags) {
			$values['tags'] = $service->attribute->tags;
		}

		// setup
		if ($service->attribute->calls) {
			$values['setup'] = $service->attribute->calls;
		}

		if ($service->attribute->options['decorator'] ?? false) {
			unset($values['factory']);

			$schema['decorator'][$service->className] = $values;

			return $schema;
		}

		// factory -> implement, if needed
		if (count($values) > 1 && isset($values['factory']) && $service->reflection->isInterface()) {
			$values['implement'] = $values['factory'];

			unset($values['factory']);
		}

		// inline factory
		if (count($values) === 1 && isset($values['factory'])) {
			$entity = $values['factory'];
		} else {
			$entity = $values;
		}

		if ($service->name) {
			$schema['services'][$service->name] = $entity;
		} else {
			$schema['services'][] = $entity;
		}

		return $schema;
	}

}
