<?php namespace Arcanedev\Currencies\Entities;

use Arcanedev\Currencies\Contracts\Entities\Currency as CurrencyContract;
use Arcanedev\Currencies\Exceptions\InvalidCurrencyArgumentException;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;

/**
 * Class     Currency
 *
 * @package  Arcanedev\Currencies\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  key
 * @property  bool    is_iso
 * @property  string  iso_numeric
 * @property  string  name
 * @property  string  symbol
 * @property  array   alternate_symbols
 * @property  string  subunit
 * @property  int     subunit_to_unit
 * @property  bool    symbol_first
 * @property  string  html_entity
 * @property  string  decimal_separator
 * @property  string  thousands_separator
 */
class Currency implements CurrencyContract, Arrayable, ArrayAccess, Jsonable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Currency attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Currency constructor.
     *
     * @param  string  $key
     * @param  array   $attributes
     */
    public function __construct($key, array $attributes)
    {
        $attributes = compact('key') + $attributes;
        $this->checkRequiredAttributes($attributes);
        $this->attributes = $attributes;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get an attribute.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return Arr::get($this->attributes, $name);
    }

    /**
     * Get required attributes.
     *
     * @return array
     */
    protected function getRequiredAttributes()
    {
        return [
            'key', 'iso_numeric', 'name', 'symbol', 'alternate_symbols', 'subunit', 'subunit_to_unit',
            'symbol_first', 'html_entity', 'decimal_separator', 'thousands_separator',
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a currency instance.
     *
     * @param  string  $key
     * @param  array   $attributes
     *
     * @return self
     */
    public static function make($key, array $attributes)
    {
        return new self($key, $attributes);
    }

    /**
     * Format the currency amount.
     *
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     */
    public function format($amount, $decimals = 2)
    {
        $formatted = number_format(
            $amount,
            $decimals,
            $this->decimal_separator,
            $this->thousands_separator
        );

        return $this->symbol_first
            ? $this->symbol.' '.$formatted
            : $formatted.' '.$this->symbol;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check the required attributes.
     *
     * @param  array  $attributes
     *
     * @throws \Arcanedev\Currencies\Exceptions\InvalidCurrencyArgumentException
     */
    private function checkRequiredAttributes(array $attributes)
    {
        $missing = array_diff($this->getRequiredAttributes(), array_keys($attributes));

        if ( ! empty($missing)) {
            throw new InvalidCurrencyArgumentException(
                'The Currency attributes are missing: ['.implode(', ', $missing).']'
            );
        }
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param  string  $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return Arr::has($this->attributes, $offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param  string  $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return Arr::get($this->attributes, $offset, null);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param  string  $offset
     * @param  mixed   $value
     */
    public function offsetSet($offset, $value) { /** DO NOTHING **/ }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param string $offset
     */
    public function offsetUnset($offset) { /** DO NOTHING **/ }
}
