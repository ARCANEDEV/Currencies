<?php namespace Arcanedev\Currencies\Contracts;

/**
 * Interface  ConverterManager
 *
 * @package   Arcanedev\Currencies\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ConverterManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions 
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a Currency converter implementation.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    public function driver($driver = null);
}
