<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Neon;

use Nette\Neon\Decoder;
use Nette\Neon\Entity;
use Nette\Neon\Exception;
use Nette\Neon\Neon;

final class NeonPrettyEncoder
{

	public static function encode(mixed $var): string
	{
		if ($var instanceof \DateTimeInterface) {
			return $var->format('Y-m-d H:i:s O');

		} elseif ($var instanceof Entity) {
			if ($var->value === Neon::CHAIN) {
				return implode('', array_map([self::class, 'encode'], $var->attributes));
			}

			return self::encode($var->value) . '('
				. (is_array($var->attributes) ? substr(self::encode($var->attributes), 1, -1) : '') . ')';
		} elseif ($var instanceof NeonComment) {
			return sprintf('# %s', $var->value);
		}

		if (is_object($var)) {
			$obj = $var;
			$var = [];
			foreach ($obj as $k => $v) {
				$var[$k] = $v;
			}
		}

		if (is_array($var)) {
			if (count($var) === 0) {
				return '[]';
			}

			$s = '';

			foreach ($var as $k => $val) {
				$v = self::encode($val);

				if ($val instanceof NeonComment) {
					$s .= $v . "\n";

					continue;
				}

				$s .= (is_numeric($k) ? '-' : self::encode($k) . ':');
				$s .= (!str_contains($v, "\n")
					? ' ' . $v . "\n"
					: "\n" . preg_replace('#^(?=.)#m', "\t", $v) . (substr($v, -2, 1) === "\n" ? '' : "\n"));
			}

			return $s;

		} elseif (is_string($var)) {
			if (!preg_match('~[\x00-\x1F]|^[+-.]?\d|^(true|false|yes|no|on|off|null)$~Di', $var)
				&& preg_match('~^' . Decoder::PATTERNS[1] . '$~Dx', $var) // 1 = literals
			) {
				return $var;
			}

			$res = json_encode($var, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

			if ($res === false) {
				throw new Exception('Invalid UTF-8 sequence: ' . $var);
			}

			if (str_contains($var, "\n")) {
				$res = preg_replace_callback('#[^\\\\]|\\\\(.)#s', function ($m) {
					return ['n' => "\n\t", 't' => "\t", '"' => '"'][$m[1] ?? ''] ?? $m[0];
				}, $res);
				$res = '"""' . "\n\t" . substr($res, 1, -1) . "\n" . '"""';
			}

			return $res;

		} elseif (is_float($var)) {
			$var = json_encode($var);

			return !str_contains($var, '.') ? $var . '.0' : $var;

		} else {
			return json_encode($var);
		}
	}

}
