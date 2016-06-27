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
     * Get the default currency iso code.
     *
     * @return string
     */
    public function getDefault();

    /**
     * Get the default currency entity.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getDefaultCurrency();

    /**
     * Get supported currencies (iso codes).
     *
     * @return array
     */
    public function getSupported();

    /**
     * Check if non ISO Currencies included.
     *
     * @return bool
     */
    public function isNonIsoIncluded();

    /**
     * Get the currencies collection.
     *
     * @return \Arcanedev\Currencies\Entities\CurrencyCollection
     */
    public function currencies();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load the currencies.
     *
     * @param  array  $currencies
     *
     * @return self
     */
    public function load(array $currencies);

    /**
     * Get a currency from the collection by iso code.
     *
     * @param  string      $iso
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     */
    public function get($iso, $default = null);

    /**
     * Get a currency or fail if not exists.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function findOrFail($iso);

    /**
     * Get the supported currencies collection.
     *
     * @return \Arcanedev\Currencies\Entities\CurrencyCollection
     */
    public function getSupportedCurrencies();

    /**
     * Format the amount.
     *
     * @param  string      $iso
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     */
    public function format($iso, $amount, $decimals = 2);

    /**
     * Format the amount by the default iso.
     *
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function formatDefault($amount, $decimals = 2);

    /**
     * Get the currency symbol by iso code.
     *
     * @param  string  $iso
     *
     * @return string
     */
    public function symbol($iso);
}
