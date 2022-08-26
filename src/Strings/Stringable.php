<?php

namespace Cruxinator\Package\Strings;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Symfony\Component\VarDumper\VarDumper;

class Stringable implements JsonSerializable
{
    use  Macroable;

    /**
     * The underlying string value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new instance of the class.
     *
     * @param string $value
     * @return void
     */
    public function __construct(string $value = '')
    {
        $this->value = (string) $value;
    }

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function after(string $search): self
    {
        return new static(MyStr::after($this->value, $search));
    }

    /**
     * Return the remainder of a string after the last occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function afterLast(string $search): self
    {
        return new static(MyStr::afterLast($this->value, $search));
    }

    /**
     * Append the given values to the string.
     *
     * @param  array  $values
     * @return static
     */
    public function append(...$values): self
    {
        return new static($this->value.implode('', $values));
    }

    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @param string $language
     * @return static
     */
    public function ascii(string $language = 'en'): self
    {
        return new static(MyStr::ascii($this->value, $language));
    }

    /**
     * Get the trailing name component of the path.
     *
     * @param string $suffix
     * @return static
     */
    public function basename(string $suffix = ''): self
    {
        return new static(basename($this->value, $suffix));
    }

    /**
     * Get the basename of the class path.
     *
     * @return static
     */
    public function classBasename(): self
    {
        return new static(class_basename($this->value));
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function before(string $search): self
    {
        return new static(MyStr::before($this->value, $search));
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function beforeLast(string $search): self
    {
        return new static(MyStr::beforeLast($this->value, $search));
    }

    /**
     * Get the portion of a string between two given values.
     *
     * @param string $from
     * @param string $to
     * @return static
     */
    public function between(string $from, string $to): self
    {
        return new static(MyStr::between($this->value, $from, $to));
    }

    /**
     * Convert a value to camel case.
     *
     * @return static
     */
    public function camel(): self
    {
        return new static(MyStr::camel($this->value));
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string|array  $needles
     * @return bool
     */
    public function contains($needles): bool
    {
        return MyStr::contains($this->value, $needles);
    }

    /**
     * Determine if a given string contains all array values.
     *
     * @param  array  $needles
     * @return bool
     */
    public function containsAll(array $needles): bool
    {
        return MyStr::containsAll($this->value, $needles);
    }

    /**
     * Get the parent directory's path.
     *
     * @param int $levels
     * @return static
     */
    public function dirname(int $levels = 1): self
    {
        return new static(dirname($this->value, $levels));
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string|array  $needles
     * @return bool
     */
    public function endsWith($needles): bool
    {
        return MyStr::endsWith($this->value, $needles);
    }

    /**
     * Determine if the string is an exact match with the given value.
     *
     * @param string $value
     * @return bool
     */
    public function exactly(string $value): bool
    {
        return $this->value === $value;
    }

    /**
     * Explode the string into an array.
     *
     * @param string $delimiter
     * @param int $limit
     * @return Collection
     */
    public function explode(string $delimiter, int $limit = PHP_INT_MAX): Collection
    {
        return collect(explode($delimiter, $this->value, $limit));
    }

    /**
     * Split a string using a regular expression or by length.
     *
     * @param  string|int  $pattern
     * @param int $limit
     * @param int $flags
     * @return Collection
     */
    public function split($pattern, int $limit = -1, int $flags = 0): Collection
    {
        if (filter_var($pattern, FILTER_VALIDATE_INT) !== false) {
            return collect(mb_str_split($this->value, $pattern));
        }

        $segments = preg_split($pattern, $this->value, $limit, $flags);

        return ! empty($segments) ? collect($segments) : collect();
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $cap
     * @return static
     */
    public function finish(string $cap): self
    {
        return new static(MyStr::finish($this->value, $cap));
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string|array  $pattern
     * @return bool
     */
    public function is($pattern): bool
    {
        return MyStr::is($pattern, $this->value);
    }

    /**
     * Determine if a given string is 7 bit ASCII.
     *
     * @return bool
     */
    public function isAscii(): bool
    {
        return MyStr::isAscii($this->value);
    }

    /**
     * Determine if a given string is a valid UUID.
     *
     * @return bool
     */
    public function isUuid(): bool
    {
        return MyStr::isUuid($this->value);
    }

    /**
     * Determine if the given string is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    /**
     * Determine if the given string is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Convert a string to kebab case.
     *
     * @return static
     */
    public function kebab(): self
    {
        return new static(MyStr::kebab($this->value));
    }

    /**
     * Return the length of the given string.
     *
     * @param string|null $encoding
     * @return int
     */
    public function length(string $encoding = null): int
    {
        return MyStr::length($this->value, $encoding);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param int $limit
     * @param string $end
     * @return static
     */
    public function limit(int $limit = 100, string $end = '...'): self
    {
        return new static(MyStr::limit($this->value, $limit, $end));
    }

    /**
     * Convert the given string to lower-case.
     *
     * @return static
     */
    public function lower(): self
    {
        return new static(MyStr::lower($this->value));
    }

    /**
     * Convert GitHub flavored Markdown into HTML.
     *
     * @param  array  $options
     * @return static
     *
    public function markdown(array $options = [])
    {
    return new static(MyStr::markdown($this->value, $options));
    }*/

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     * @return static
     */
    public function match(string $pattern): self
    {
        return new static(MyStr::match($pattern, $this->value));
    }

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     * @return Collection
     */
    public function matchAll(string $pattern): Collection
    {
        return MyStr::matchAll($pattern, $this->value);
    }

    /**
     * Determine if the string matches the given pattern.
     *
     * @param string $pattern
     * @return bool
     */
    public function test(string $pattern): bool
    {
        return $this->match($pattern)->isNotEmpty();
    }

    /**
     * Pad both sides of the string with another.
     *
     * @param int $length
     * @param string $pad
     * @return static
     */
    public function padBoth(int $length, string $pad = ' '): self
    {
        return new static(str_pad($this->value, $length, $pad, STR_PAD_BOTH));
    }

    /**
     * Pad the left side of the string with another.
     *
     * @param int $length
     * @param string $pad
     * @return static
     */
    public function padLeft(int $length, string $pad = ' '): self
    {
        return new static(str_pad($this->value, $length, $pad, STR_PAD_LEFT));
    }

    /**
     * Pad the right side of the string with another.
     *
     * @param int $length
     * @param string $pad
     * @return static
     */
    public function padRight(int $length, string $pad = ' '): self
    {
        return new static(MyStr::padRight($this->value, $length, $pad));
    }

    /**
     * Parse a Class style callback into class and method.
     *
     * @param string|null $default
     * @return array
     */
    public function parseCallback(string $default = null): array
    {
        return MyStr::parseCallback($this->value, $default);
    }

    /**
     * Call the given callback and return a new string.
     *
     * @param  callable  $callback
     * @return static
     */
    public function pipe(callable $callback): self
    {
        return new static(call_user_func($callback, $this));
    }

    /**
     * Get the plural form of an English word.
     *
     * @param int $count
     * @return static
     */
    public function plural(int $count = 2): self
    {
        return new static(MyStr::plural($this->value, $count));
    }

    /**
     * Pluralize the last word of an English, studly caps case string.
     *
     * @param int $count
     * @return static
     */
    public function pluralStudly(int $count = 2): self
    {
        return new static(MyStr::pluralStudly($this->value, $count));
    }

    /**
     * Prepend the given values to the string.
     *
     * @param  array  $values
     * @return static
     */
    public function prepend(...$values): self
    {
        return new static(implode('', $values).$this->value);
    }

    /**
     * Remove any occurrence of the given string in the subject.
     *
     * @param  string|array<string>  $search
     * @param bool $caseSensitive
     * @return static
     */
    public function remove($search, bool $caseSensitive = true): self
    {
        $subject = $caseSensitive
            ? str_replace($search, '', $this->value)
            : str_ireplace($search, '', $this->value);

        return new static($subject);
    }

    /**
     * Repeat the string.
     *
     * @param  int  $times
     * @return static
     */
    public function repeat(int $times): self
    {
        return new static(
            str_repeat($this->value, $times)
        );
    }

    /**
     * Replace the given value in the given string.
     *
     * @param  string|string[]  $search
     * @param  string|string[]  $replace
     * @return static
     */
    public function replace($search, $replace): self
    {
        return new static(MyStr::replace($search, $replace, $this->value));
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array $replace
     * @return static
     */
    public function replaceArray(string $search, array $replace): self
    {
        return new static(MyStr::replaceArray($search, $replace, $this->value));
    }

    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @return static
     */
    public function replaceFirst(string $search, string $replace): self
    {
        return new static(MyStr::replaceFirst($search, $replace, $this->value));
    }

    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @return static
     */
    public function replaceLast(string $search, string $replace): self
    {
        return new static(MyStr::replaceLast($search, $replace, $this->value));
    }

    /**
     * Replace the patterns matching the given regular expression.
     *
     * @param string $pattern
     * @param Closure|string $replace
     * @param int $limit
     * @return static
     */
    public function replaceMatches(string $pattern, $replace, int $limit = -1): self
    {
        if ($replace instanceof Closure) {
            return new static(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return new static(preg_replace($pattern, $replace, $this->value, $limit));
    }

    /**
     * Begin a string with a single instance of a given value.
     *
     * @param string $prefix
     * @return static
     */
    public function start(string $prefix): self
    {
        return new static(MyStr::start($this->value, $prefix));
    }

    /**
     * Convert the given string to upper-case.
     *
     * @return static
     */
    public function upper(): self
    {
        return new static(MyStr::upper($this->value));
    }

    /**
     * Convert the given string to title case.
     *
     * @return static
     */
    public function title(): self
    {
        return new static(MyStr::title($this->value));
    }

    /**
     * Get the singular form of an English word.
     *
     * @return static
     */
    public function singular(): self
    {
        return new static(MyStr::singular($this->value));
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $separator
     * @param string|null $language
     * @return static
     */
    public function slug(string $separator = '-', ?string $language = 'en'): self
    {
        return new static(MyStr::slug($this->value, $separator, $language));
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $delimiter
     * @return static
     */
    public function snake(string $delimiter = '_'): self
    {
        return new static(MyStr::snake($this->value, $delimiter));
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string|array  $needles
     * @return bool
     */
    public function startsWith($needles): bool
    {
        return MyStr::startsWith($this->value, $needles);
    }

    /**
     * Convert a value to studly caps case.
     *
     * @return static
     */
    public function studly(): self
    {
        return new static(MyStr::studly($this->value));
    }

    /**
     * Returns the portion of the string specified by the start and length parameters.
     *
     * @param int $start
     * @param int|null $length
     * @return static
     */
    public function substr(int $start, int $length = null): self
    {
        return new static(MyStr::substr($this->value, $start, $length));
    }

    /**
     * Returns the number of substring occurrences.
     *
     * @param string $needle
     * @param int|null $offset
     * @param int|null $length
     * @return int
     */
    public function substrCount(string $needle, int $offset = null, int $length = null): int
    {
        return MyStr::substrCount($this->value, $needle, $offset ?? 0, $length);
    }

    /**
     * Trim the string of the given characters.
     *
     * @param string|null $characters
     * @return static
     */
    public function trim(string $characters = null): self
    {
        return new static(trim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Left trim the string of the given characters.
     *
     * @param string|null $characters
     * @return static
     */
    public function ltrim(string $characters = null): self
    {
        return new static(ltrim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Right trim the string of the given characters.
     *
     * @param string|null $characters
     * @return static
     */
    public function rtrim(string $characters = null): self
    {
        return new static(rtrim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Make a string's first character uppercase.
     *
     * @return static
     */
    public function ucfirst(): self
    {
        return new static(MyStr::ucfirst($this->value));
    }

    /**
     * Execute the given callback if the string is empty.
     *
     * @param callable $callback
     * @return static
     */
    public function whenEmpty(callable $callback): self
    {
        if ($this->isEmpty()) {
            $result = $callback($this);

            return is_null($result) ? $this : $result;
        }

        return $this;
    }

    /**
     * Execute the given callback if the string is not empty.
     *
     * @param callable $callback
     * @return static
     */
    public function whenNotEmpty(callable $callback): self
    {
        if ($this->isNotEmpty()) {
            $result = $callback($this);

            return is_null($result) ? $this : $result;
        }

        return $this;
    }

    /**
     * Limit the number of words in a string.
     *
     * @param int $words
     * @param string $end
     * @return static
     */
    public function words(int $words = 100, string $end = '...'): self
    {
        return new static(MyStr::words($this->value, $words, $end));
    }

    /**
     * Get the number of words a string contains.
     *
     * @return int
     */
    public function wordCount(): int
    {
        return str_word_count($this->value);
    }

    /**
     * Dump the string.
     *
     * @return $this
     */
    public function dump(): self
    {
        VarDumper::dump($this->value);

        return $this;
    }

    /**
     * Dump the string and end the script.
     *
     * @return void
     */
    public function dd()
    {
        $this->dump();

        exit(1);
    }

    /**
     * Convert the object to a string when JSON encoded.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    /**
     * Proxy dynamic properties onto methods.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->{$key}();
    }

    /**
     * Get the raw string value.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
