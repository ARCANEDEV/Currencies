<?php namespace Arcanedev\Currencies\Contracts\Entities;

/**
 * Interface  Currency
 *
 * @package   Arcanedev\Currencies\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Currency
{
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
    public function format($amount, $decimals = 2);
}
