<?php

namespace App\Http\Traits;

use Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException;

trait HandleEnum
{
    /** Return the enum's value when it's $invoked(). */

    /**
     * Return the enum's value when it's $invoked().
     *
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return $this->value;
    }

    /**
     * Return the enum's value or name when it's called ::STATICALLY().
     *
     * @param mixed $name
     * @param mixed $args
     *
     * @return mixed
     */
    public static function __callStatic(mixed $name, mixed $args): mixed
    {
        $cases = static::cases();

        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case->value;
            }
        }

        throw new BackedEnumCaseNotFoundException(static::class, $name);
    }

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            /* @todo `value` is required for each enum meta array */
        ];
    }

    /**
     * Get meta property by value.
     *
     * @param mixed $value
     *
     * @return array
     */
    public static function getMetaByValue($value): array
    {
        $caseIndex = array_search($value, array_column(static::cases(), 'value'));
        $index = $caseIndex !== false
        ? array_search(static::cases()[$caseIndex], array_column(static::metaProperties(), 'value'))
        : false;

        return $index !== false ? static::metaProperties()[$index] : [];
    }

    /**
     * Render meta property.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function meta(?string $property = null): mixed
    {
        $meta = static::metaProperties();
        $index = array_search(static::getCaseByValue($this->value), array_column($meta, 'value'));

        return !$property ? $meta[$index]
        : ($meta[$index][$property] ?? null);
    }

    /**
     * List all values in option format.
     *
     * @param string $orderBy
     * @param array $cases
     *
     * @return array
     */
    public static function getOptionList(string $orderBy = 'value', array $cases = []): array
    {
        $arr = [];

        foreach ($cases ?: static::cases() as $case) {
            $arr[] = [
                'id' => $case->value,
                'value' => $case->meta('title'),
                'label' => $case->meta('title'),
            ];
        }

        if ($orderBy) {
            array_multisort(array_column($arr, $orderBy), SORT_ASC, $arr);
        }

        return $arr;
    }

    /**
     * Render array for radio looping.
     *
     * @return array
     */
    public static function getRadioList(): array
    {
        $arr = [];

        foreach (static::cases() as $case) {
            $arr[] = [
                'id' => $case->value,
                'label' => $case->meta('title'),
                'value' => static::getName($case->value),
                'radioSelected' => $case->value,
            ];
        }

        return $arr;
    }

    /**
     * Get case of enum by its value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function getCaseByValue(mixed $value): mixed
    {
        $index = array_search($value, array_column(static::cases(), 'value'));
        return $index !== false ? static::cases()[$index] : null;
    }

    /**
     * Get case of enum by its name.
     *
     * @param mixed $name
     *
     * @return mixed
     */
    public static function getCaseByName(mixed $name): mixed
    {
        $index = array_search($name, array_column(static::cases(), 'name'));
        return $index !== false ? static::cases()[$index] : null;
    }

    /**
     * Get name of enum by its value.
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function getName(mixed $value): string
    {
        $case = static::getCaseByValue($value);
        $words = $case ? explode('_', strtolower($case->name)) : [];
        return ucwords(implode(' ', $words));
    }

    /**
     * Get all values.
     *
     * @return array
     */
    public static function getAllValues(): array
    {
        return array_map(fn($item) => $item->value, static::cases());
    }

    /**
     * Get a random value.
     *
     * @return mixed
     */
    public static function getRandomValue(): mixed
    {
        $values = static::getAllValues();
        $index = rand(1, count($values)) - 1;

        return $values[$index];
    }

    /**
     * Get case by meta
     *
     * @param string $field
     * @param mixed $value
     *
     * @return mixed
     */
    public static function getCaseByMeta(string $field, mixed $value): mixed
    {
        $index = array_search($value, array_column(static::metaProperties(), $field));

        if ($index !== false) {
            $property = static::metaProperties()[$index];

            return $property['value'];
        }

        return null;
    }

    /**
     * Render pill
     *
     * @return array
     */
    public function pill(): array
    {
        return [
            'title' => $this->meta('title'),
            'color' => $this->meta('color'),
        ];
    }
}
