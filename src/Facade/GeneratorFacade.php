<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Facade;

use Nette\Utils\Finder;
use WebChemistry\ServiceAttribute\DecoratorFinder;
use WebChemistry\ServiceAttribute\Entity\DecoratorEntity;
use WebChemistry\ServiceAttribute\Entity\ServiceEntity;
use WebChemistry\ServiceAttribute\Generator\DecoratorNeonGenerator;
use WebChemistry\ServiceAttribute\Generator\NeonFile;
use WebChemistry\ServiceAttribute\Generator\ServiceNeonGenerator;
use WebChemistry\ServiceAttribute\ServiceFinder;
use WebChemistry\ServiceAttribute\Validator\DecoratorValidator;

final class GeneratorFacade
{

	private string $generateDirectory;

	private Finder $directory;

	public function __construct(
		string $generateDirectory,
		string|Finder $directory,
	)
	{
		$this->generateDirectory = rtrim($generateDirectory, '/');
		$this->directory = is_string($directory) ? Finder::find('*.php')->from($directory) : $directory;
	}

	/**
	 * @return ServiceEntity[]
	 */
	public function generateServices(string $fileName = 'services.neon', bool $diff = true): array
	{
		$neon = new NeonFile(
			$this->generateDirectory . '/' . $fileName,
			(new ServiceNeonGenerator($services = ServiceFinder::findServices($this->directory)))->generate()
		);

		if ($diff) {
			$neon->diff();
		}

		$neon->save();

		return $services;
	}

	/**
	 * @return DecoratorEntity[]
	 */
	public function generateDecorators(string $fileName = 'decorators.neon', bool $diff = true, bool $validate = true): array
	{
		$decorators = DecoratorFinder::findDecorators($this->directory);

		if ($validate) {
			DecoratorValidator::validate($decorators);
		}

		$neon = new NeonFile(
			$this->generateDirectory . '/' . $fileName,
			(new DecoratorNeonGenerator($decorators))->generate()
		);

		if ($diff) {
			$neon->diff();
		}

		$neon->save();

		return $decorators;
	}

}
