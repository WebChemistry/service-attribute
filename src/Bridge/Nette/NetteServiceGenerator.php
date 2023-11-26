<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Bridge\Nette;

use Nette\Neon\Neon;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;

final class NetteServiceGenerator
{

	/**
	 * @param ServiceEntity[] $services
	 */
	public static function generate(array $services): ?string
	{
		$neon = [
			'services' => [],
		];

		foreach ($services as $service) {
			self::generateStructure($service, $neon);
		}

		return $neon ? Neon::encode($neon, true) : null;
	}

	/**
	 * @param mixed[] $struct
	 */
	private static function generateStructure(ServiceEntity $service, array &$struct): void
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

			$struct['decorator'][$service->className] = $values;

			return;
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
			$struct['services'][$service->name] = $entity;
		} else {
			$struct['services'][] = $entity;
		}
	}

}
