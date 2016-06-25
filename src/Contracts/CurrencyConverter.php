<?php namespace Arcanedev\Currencies\Contracts;

/**
 * Interface  CurrencyConverter
 *
 * @package   Arcanedev\Currencies\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CurrencyConverter
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get currencies rates.
     *
     * @param  string|null        $from
     * @param  array|string|null  $to
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function rates($from = null, $to = null);
}
