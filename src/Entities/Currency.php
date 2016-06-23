<?php namespace Arcanedev\Currencies\Entities;

use Arcanedev\Currencies\Contracts\Entities\Currency as CurrencyContract;
use Arcanedev\Currencies\Exceptions\InvalidCurrencyArgumentException;
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
class Currency implements CurrencyContract, Jsonable
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

    /**
     * Currency required attributes.
     *
     * @var array
     */
    protected $required = [
        'key', 'iso_numeric', 'name', 'symbol', 'alternate_symbols', 'subunit', 'subunit_to_unit',
        'symbol_first', 'html_entity', 'decimal_separator', 'thousands_separator',
    ];

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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Format the currency amount.
     *
     * @param  int  $amount    -  Amount in cents
     * @param  int  $decimals
     *
     * @return string
     */
    public function format($amount, $decimals = 2)
    {
        $formatted = number_format(
            $amount / $this->subunit_to_unit,
            $decimals,
            $this->decimal_separator,
            $this->thousands_separator
        );

        return $this->symbol_first
            ? $this->symbol.' '.$formatted
            : $formatted.' '.$this->symbol;
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
        return json_encode($this->attributes, $options);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function checkRequiredAttributes(array $attributes)
    {
        $missing = array_diff($this->required, array_keys($attributes));

        if ( ! empty($missing)) {
            throw new InvalidCurrencyArgumentException(
                'The Currency attributes are missing: ['.implode(', ', $missing).']'
            );
        }
    }
}
