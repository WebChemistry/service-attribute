<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use LogicException;
use Nette\Utils\Finder;
use PhpToken;
use WeakMap;

final class ClassFinder
{

	private static WeakMap $caching;

	public static function findClasses(Finder $directory): iterable
	{
		if (!isset(self::getCaching()[$directory])) {
			$cache = [];
			foreach ($directory as $file) {
				$classes = self::findClassNames((string) $file);
				if (!$classes) {
					continue;
				}

				$cache = array_merge($cache, $classes);
			}

			self::getCaching()[$directory] = $cache;
		}

		return self::$caching[$directory];
	}

	/**
	 * @return string[]
	 */
	private static function findClassNames(string $file): array
	{
		if (($contents = file_get_contents($file)) === false) {
			throw new LogicException(sprintf('Cannot get content from %s', $file));
		}

		if (strlen($contents) > 50000) {
			echo sprintf("File %s is too big, skipped.\n", $file);

			return [];
		}

		$classes = [];
		$tokens = new TokenIterator(PhpToken::tokenize($contents));
		$namespace = null;
		$name = null;
		while ($token = $tokens->next()) {

			if ($token->is(T_NAMESPACE)) {
				$namespace = null;

				while ($token = $tokens->next()) {
					if ($token->is([';', '{'])) {
						break;
					}

					$namespace .= $token->text;
				}

				$namespace = trim($namespace);
			}

			if ($token->is([T_CLASS, T_INTERFACE])) {
				$space = $tokens->next();

				if ($space?->text !== ' ') {
					continue;
				}

				$string = $tokens->next();
				if ($string?->id !== T_STRING) {
					continue;
				}

				$classes[] = ($namespace ? $namespace . '\\' : '') . $string->text;
			}
		}

		return $classes;
	}

	private static function getCaching(): WeakMap
	{
		if (!isset(self::$caching)) {
			self::$caching = new WeakMap();
		}

		return self::$caching;
	}

}
