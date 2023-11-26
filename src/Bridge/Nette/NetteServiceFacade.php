<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Bridge\Nette;

use Nette\Utils\Finder;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Filter\ServiceFilter;
use WebChemistry\ServiceAttribute\Generator\NeonFile;
use WebChemistry\ServiceAttribute\ServiceFinder;

final class NetteServiceFacade
{

	/**
	 * @param string[]|Finder $paths
	 * @param string $outputFile
	 * @return ServiceEntity[]
	 */
	public static function generate(string $name, array|Finder $paths, string $outputFile, array $categories = []): array
	{
		echo sprintf("\e[36m%s\e[39m\n", $name);

		$filter = new ServiceFilter(
			ServiceFinder::findServices(is_array($paths) ? Finder::findFiles('*.php')->from(...$paths) : $paths),
		);

		if ($categories) {
			$filter->requireCategories($categories);
		}

		$services = $filter->all();

		$file = new NeonFile($outputFile, NetteServiceGenerator::generate($services));

		$file->diff();
		$file->save();

		echo sprintf("File generated from %d services: file://%s\n", count($services), $outputFile);

		return $services;
	}

}
