<?php namespace Arcanedev\Currencies\Contracts;

/**
 * Interface  CurrencyManager
 *
 * @package   Arcanedev\Currencies\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CurrencyManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default currency entity.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getDefaultCurrency();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a currency by ISO.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function get($iso);

    /**
     * Get all currencies.
     *
     * @return array
     */
    public function allCurrencies();

    /**
     * Load the currencies.
     *
     * @param  array  $currencies
     *
     * @return self
     */
    public function load(array $currencies);

    /**
     * Format the amount.
     *
     * @param  string  $iso
     * @param  int     $amount
     * @param  int     $decimals
     *
     * @return string
     */
    public function format($iso, $amount, $decimals = 2);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if non ISO Currencies included.
     *
     * @return bool
     */
    public function isNonIsoIncluded();
}
