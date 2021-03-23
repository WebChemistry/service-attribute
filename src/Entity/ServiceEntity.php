<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Entity;

use Nette\Neon\Neon;
use ReflectionClass;

final class ServiceEntity implements ServiceEntityInterface
{

	public function __construct(
		public ReflectionClass $reflection,
		public string $className,
		public ?string $name,
		public ?string $args,
		public ?array $tags,
	)
	{
	}

	public function generate(): array
	{
		if (!$this->tags) {
			return [$this->generateIndex() . $this->className . $this->generateArgs()];
		}

		$lines = [
			$this->generateIndex(),
		];

		return $this->generateSections($lines, [
			'class' => $this->className,
			'arguments' => $this->args,
			'tags' => $this->tags ? Neon::encode($this->tags) : null,
		]);
	}

	private function generateIndex(): string
	{
		if ($this->name) {
			return $this->name . ': ';
		} else {
			return '- ';
		}
	}

	private function generateArgs(): string
	{
		if ($this->args) {
			return '(' . $this->args . ')';
		}

		return '';
	}

	private function generateSections(array $base, array $sections): array
	{
		foreach ($sections as $name => $value) {
			if (!$value) {
				continue;
			}

			$base[] = sprintf("\t%s: %s", $name, $value);
		}

		return $base;
	}

}
