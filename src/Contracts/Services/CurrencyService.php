<?php namespace Arcanedev\Currencies\Contracts\Services;

/**
 * Interface  CurrencyService
 *
 * @package   Arcanedev\Currencies\Contracts\Services
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CurrencyService
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default currency.
     *
     * @return string
     */
    public function getDefault();

    /**
     * Get the supported currencies.
     *
     * @return array
     */
    public function getSupported();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get currencies rates.
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function rates();

    /**
     * Get supported currencies rates.
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function supportedRates();
}
