<?php
/**
 * Functions to parse the Accept-Language header.
 */

/**
 * Parses the country code from Accept-Language.
 * 
 * @return Two-letter country code (lowercase), or null if not found.
 */
function getCountryCode(string $value): ?string
{
    $valuesWithPreference = [];
    foreach (explode(',', $value) as $item) {
        $parsed = parseSingleItem(trim($item));
        if (!$parsed) continue;
        list($code, $preference) = $parsed;
        $valuesWithPreference[$code] = $preference;
    }

    arsort($valuesWithPreference);

    return $valuesWithPreference ? array_keys($valuesWithPreference)[0] : null;
}

function parseSingleItem(string $item): ?array
{
    $defaultPriority = 1;

    preg_match('/^([^;]*)(?:;q=([\d.]+))?$/', trim($item), $matches);
    $code = getCountryFromCode($matches[1]);

    return $code
        ? [$code, $matches[2] ?? $defaultPriority]
        : null;
}

function getCountryFromCode(string $value): ?string
{
    if (preg_match('/^\w\w-(\w\w)$/', $value, $matches))
        return strtolower($matches[1]);

    return null;
}
