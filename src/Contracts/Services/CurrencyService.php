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
