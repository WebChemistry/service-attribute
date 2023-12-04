<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Util;

use Nette\Utils\FileSystem;

final class FileDiffer
{

	public function __construct(
		private string $file,
		private string $content,
	)
	{
	}

	public function diff(): void
	{
		if (!file_exists($this->file)) {
			echo "\e[34mNew file generated!\e[39m\n";

			return;
		}

		exec(
			sprintf(
				'diff  <(echo \'%s\' ) <(echo \'%s\')',
				file_get_contents($this->file),
				$this->content,
			),
			$output,
			$result
		);

		if ($result === 0) {
			echo "\e[34mNo changes!\e[39m\n";
		} else {
			echo implode("\n", array_map(
					fn (string $line) => $this->decorateLine($line),
					$output,
				)) . "\n";
		}
	}

	public function save(): void
	{
		FileSystem::write($this->file, $this->content);
	}

	private function decorateLine(string $line): string
	{
		$char = $line[0] ?? '';

		if ($char === '<') {
			$line = "\e[31m-" . substr($line, 1) . "\e[39m";
		} elseif ($char === '>') {
			$line = "\e[32m+" . substr($line, 1) . "\e[39m";
		}

		return $line;
	}

}
