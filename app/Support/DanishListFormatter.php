<?php

declare(strict_types=1);

namespace App\Support;

final class DanishListFormatter
{
    /**
     * @param list<string> $items Non-empty trimmed strings (callers filter empties)
     */
    public static function formatDanishAndList(array $items): string
    {
        $items = \array_values(\array_filter(\array_map(trim(...), $items), static fn(string $s): bool => $s !== ''));

        $count = \count($items);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $items[0];
        }

        if ($count === 2) {
            return $items[0] . ' og ' . $items[1];
        }

        $last = \array_pop($items);

        return \implode(', ', $items) . ' og ' . $last;
    }

    /**
     * Som {@see formatDanishAndList}, men ved flere kirker fjernes suffikset « Kirke» fra alle undtagen den sidste,
     * fx. «Holsted Kirke» + «Sankt Peders Kirke» → «Holsted og Sankt Peders Kirke».
     *
     * @param array<int, string> $churchNames
     */
    public static function formatDanishAndChurchList(array $churchNames): string
    {
        $names = \array_values(\array_filter(\array_map(trim(...), $churchNames), static fn(string $s): bool => $s !== ''));

        $count = \count($names);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $names[0];
        }

        $suffix = ' Kirke';
        $parts = [];

        foreach ($names as $i => $name) {
            $isLast = $i === $count - 1;

            if (!$isLast && \str_ends_with($name, $suffix)) {
                $parts[] = \substr($name, 0, -\strlen($suffix));
            } else {
                $parts[] = $name;
            }
        }

        return self::formatDanishAndList($parts);
    }
}
