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
				$class = self::findClassName((string) $file);
				if ($class === null) {
					continue;
				}

				$cache[] = $class;
			}

			self::getCaching()[$directory] = $cache;
		}

		return self::$caching[$directory];
	}

	private static function findClassName(string $file): ?string
	{
		if (($contents = file_get_contents($file)) === false) {
			throw new LogicException(sprintf('Cannot get content from %s', $file));
		}

		$tokens = new TokenIterator(PhpToken::tokenize($contents));
		$namespace = null;
		$name = null;
		while ($token = $tokens->next()) {
			if ($token->is(T_NAMESPACE)) {
				$tokens->nextUntil(';');

				$namespace = $tokens->getPrevious()?->text;
			}

			if ($token->is(T_CLASS) || $token->is(T_INTERFACE)) {
				$token = $tokens->nextUntil('T_STRING');
				$name = $token?->text;

				break;
			} elseif ($token->is(T_TRAIT)) {
				break;
			}
		}

		if ($name === null) {
			return null;
		}

		return ($namespace ? $namespace . '\\' : '') . $name;
	}

	private static function getCaching(): WeakMap
	{
		if (!isset(self::$caching)) {
			self::$caching = new WeakMap();
		}

		return self::$caching;
	}

}
