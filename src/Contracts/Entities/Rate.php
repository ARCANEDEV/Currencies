<?php namespace Arcanedev\Currencies\Contracts\Entities;

/**
 * Interface  Rate
 *
 * @package   Arcanedev\Currencies\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Rate
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the `from` currency iso.
     *
     * @return string
     */
    public function from();

    /**
     * Get the `from` currency.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getFromCurrency();

    /**
     * Get the `to` currency iso.
     *
     * @return string
     */
    public function to();

    /**
     * Get the `to` currency.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getToCurrency();

    /**
     * Get the rate ratio.
     *
     * @return int|double
     */
    public function ratio();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a rate instance.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  double  $ratio
     *
     * @return self
     */
    public static function make($from, $to, $ratio);

    /**
     * Convert the amount.
     *
     * @param  double|int  $amount
     *
     * @return double|int
     */
    public function convert($amount);
}
