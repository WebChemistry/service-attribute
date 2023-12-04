<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Bridge\Nette;

use Nette\Utils\Finder;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Filter\ServiceFilter;
use WebChemistry\ServiceAttribute\Generator\ServiceGenerator;
use WebChemistry\ServiceAttribute\Output\NeonOutput;
use WebChemistry\ServiceAttribute\Plugin\NetteServicePlugin;
use WebChemistry\ServiceAttribute\Plugin\ServicePlugin;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Util\FileDiffer;

final class NetteServiceFacade
{

	/**
	 * @param string[]|Finder $paths
	 * @param string $outputFile
	 * @param string[] $categories
	 * @param ServicePlugin[] $plugins
	 * @return ServiceEntity[]
	 */
	public static function generate(
		string $name,
		array|Finder $paths,
		string $outputFile,
		array $categories = [],
		array $plugins = [],
	): array
	{
		echo sprintf("\e[36m%s\e[39m\n", $name);

		$filter = new ServiceFilter(
			ServiceFinder::findServices(is_array($paths) ? Finder::findFiles('*.php')->from(...$paths) : $paths),
		);

		if ($categories) {
			$filter->requireCategories($categories);
		}

		$services = $filter->all();

		$plugins[] = new NetteServicePlugin();

		$generator = new ServiceGenerator($plugins);

		$file = new FileDiffer($outputFile, $generator->generate($services, new NeonOutput()));

		$file->diff();
		$file->save();

		echo sprintf("File generated from %d services: file://%s\n", count($services), $outputFile);

		return $services;
	}

}
