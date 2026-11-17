<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Config;

use Illuminate\Support\Facades\Config;

class ConfigUtils
{
    /** Like Config::string(), but returns null when the key holds null, e.g. from an unset env variable. */
    public static function tryString(string $key): ?string
    {
        $value = Config::get($key);
        if ($value !== null && ! is_string($value)) {
            $type = gettype($value);
            throw new \InvalidArgumentException("Configuration value for key [{$key}] must be a string or null, {$type} given.");
        }

        return $value;
    }
}
