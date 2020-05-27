<?php
/**
 * Functions to parse the Accept-Language header.
 */

/**
 * Parses the country code from Accept-Language.
 * 
 * If the value contains multiple codes, chooses the one with the highest priority.
 * 
 * @param string $value Accept-Language value
 * @param array $availableLanguages Choose only from these languages (default = all)
 * 
 * @return string|null Two-letter country code (lowercase), or null if not found.
 */
function getCountryCode(string $value, array $availableLanguages = []): ?string
{
    $valuesWithPreference = [];
    foreach (explode(',', $value) as $item) {
        $parsed = parseSingleItem(trim($item));
        if (!$parsed) continue;

        list($code, $preference) = $parsed;
        if ($availableLanguages && !in_array($code, $availableLanguages)) continue;

        $valuesWithPreference[$code] = $preference;
    }

    arsort($valuesWithPreference);

    return $valuesWithPreference ? array_keys($valuesWithPreference)[0] : null;
}

/**
 * Parses a single Accept-Language item.
 * 
 * @param string $item Language code and optional priority: code|code;q=priority
 * 
 * @return array|null [country code, priority], or null if no country code found
 */
function parseSingleItem(string $item): ?array
{
    $defaultPriority = 1;

    preg_match('/^([^;]*)(?:;q=([\d.]+))?$/', trim($item), $matches);
    $code = parseCountryFromCode($matches[1]);

    return $code
        ? [$code, $matches[2] ?? $defaultPriority]
        : null;
}

/**
 * Tries to parse a country code from the language code.
 * 
 * If the code is in the format 'll-cc', it is assumed that 'cc' is a two-letter
 * country code.
 * 
 * @param string $value Language code
 * 
 * @return string|null Country code, or null if not found.
 */
function parseCountryFromCode(string $value): ?string
{
    if (preg_match('/^\w\w-(\w\w)$/', $value, $matches))
        return strtolower($matches[1]);

    return null;
}
