<?php namespace Arcanedev\Currencies\Contracts\Entities;

/**
 * Interface  Currency
 *
 * @package   Arcanedev\Currencies\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
interface Currency
{
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
    public static function make($key, array $attributes);

    /**
     * Format the currency amount.
     *
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     */
    public function format($amount, $decimals = 2);
}
