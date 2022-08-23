<?php

namespace Cruxinator\Package\Strings;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MyStr extends Str
{
    /**
     * Return the remainder of a string after the last occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     * @return string
     */
    public static function afterLast($subject, $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $position = strrpos($subject, (string) $search);

        if ($position === false) {
            return $subject;
        }

        return substr($subject, $position + strlen($search));
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     * @return string
     */
    public static function beforeLast($subject, $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $pos = mb_strrpos($subject, $search);

        if ($pos === false) {
            return $subject;
        }

        return self::substr($subject, 0, $pos);
    }

    /**
     * Get the portion of a string between two given values.
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @return string
     */
    public static function between($subject, $from, $to): string
    {
        if ($from === '' || $to === '') {
            return $subject;
        }

        return static::beforeLast(self::after($subject, $from), $to);
    }

    /**
     * Pad both sides of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padBoth($value, $length, $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_BOTH);
    }

    /**
     * Pad the left side of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padLeft($value, $length, $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_LEFT);
    }

    /**
     * Pad the right side of a string with another.
     *
     * @param string $value
     * @param int $length
     * @param string $pad
     * @return string
     */
    public static function padRight($value, $length, $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_RIGHT);
    }

    /**
     * Repeat the given string.
     *
     * @param  string  $string
     * @param  int  $times
     * @return string
     */
    public static function repeat(string $string, int $times): string
    {
        return str_repeat($string, $times);
    }

    /**
     * Determine if a given string contains all array values.
     *
     * @param string $haystack
     * @param string[] $needles
     * @param bool $ignoreCase
     * @return bool
     */
    public static function containsAll($haystack, array $needles, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $haystack = mb_strtolower($haystack);
            $needles = array_map('mb_strtolower', $needles);
        }

        foreach ($needles as $needle) {
            if (! self::contains($haystack, $needle)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if a given string is 7 bit ASCII.
     *
     * @param string $value
     * @return bool
     */
    public static function isAscii($value): bool
    {
        //return ASCII::is_ascii((string) $value);
    }

    /**
     * Determine if a given string is a valid UUID.
     *
     * @param string $value
     * @return bool
     */
    public static function isUuid($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return preg_match('/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD', $value) > 0;
    }

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     * @param string $subject
     * @return string
     */
    public static function match($pattern, $subject): string
    {
        preg_match($pattern, $subject, $matches);

        if (! $matches) {
            return '';
        }

        return $matches[1] ?? $matches[0];
    }

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     * @param string $subject
     * @return Collection
     */
    public static function matchAll($pattern, $subject): Collection
    {
        preg_match_all($pattern, $subject, $matches);

        if (empty($matches[0])) {
            return collect();
        }

        return collect($matches[1] ?? $matches[0]);
    }

    /**
     * Pluralize the last word of an English, studly caps case string.
     *
     * @param string $value
     * @param int $count
     * @return string
     */
    public static function pluralStudly($value, $count = 2): string
    {
        $parts = preg_split('/(.)(?=[A-Z])/u', $value, -1, PREG_SPLIT_DELIM_CAPTURE);

        $lastWord = array_pop($parts);

        return implode('', $parts).self::plural($lastWord, $count);
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array<int|string, string> $replace
     * @param string|mixed $subject
     * @return string
     */
    public static function replaceArray($search, array $replace, $subject): string
    {
        $segments = explode($search, $subject);

        $result = array_shift($segments);

        foreach ($segments as $segment) {
            $result .= (array_shift($replace) ?? $search).$segment;
        }

        return $result;
    }

    /**
     * Replace the given value in the given string.
     *
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @param  string|string[]  $subject
     * @return string
     */
    public static function replace($search, $replace, $subject): string
    {
        return str_replace($search, $replace, $subject);
    }

    /**
     * Returns the number of substring occurrences.
     *
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @param int|null $length
     * @return int
     */
    public static function substrCount($haystack, $needle, $offset = 0, $length = null): int
    {
        if (! is_null($length)) {
            return substr_count($haystack, $needle, $offset, $length);
        } else {
            return substr_count($haystack, $needle, $offset);
        }
    }

    public static function isTrueable(?string $string): bool
    {
        return 'true' == strtolower($string) || '1' == $string;
    }

    public static function asStringable($string): Stringable
    {
        return new Stringable($string);
    }
}
