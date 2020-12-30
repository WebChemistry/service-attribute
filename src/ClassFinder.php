<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute;

use LogicException;
use Nette\Utils\Finder;
use PhpToken;

final class ClassFinder
{

	/** @var mixed[] */
	private static array $caching = [];

	public static function findClasses(string $directory): iterable
	{
		if (!isset(self::$caching[$directory])) {
			foreach (Finder::findFiles('*.php')->from($directory) as $file) {
				$class = self::findClassName((string) $file);
				if ($class === null) {
					continue;
				}

				self::$caching[$directory][] = $class;
			}
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

}
