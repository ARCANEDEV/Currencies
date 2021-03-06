<?php namespace Arcanedev\Currencies\Tests;

use Arcanedev\Currencies\Entities\Currency;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\Currencies\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->app->loadDeferredProviders();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\Currencies\CurrenciesServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Define your environment setup.
    }

    /* ------------------------------------------------------------------------------------------------
     |  Custom Assertions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Assert the currency entity.
     *
     * @param  string  $key
     * @param  array   $attributes
     * @param  mixed   $currency
     */
    protected function assertCurrencyEntity($key, array $attributes, $currency)
    {
        $this->assertCurrencyEntityInstance($currency);

        $this->assertSame($key,                               $currency->key);
        $this->assertSame($attributes['iso_numeric'],         $currency->iso_numeric);
        $this->assertSame($attributes['name'],                $currency->name);
        $this->assertSame($attributes['symbol'],              $currency->symbol);
        $this->assertSame($attributes['alternate_symbols'],   $currency->alternate_symbols);
        $this->assertSame($attributes['subunit'],             $currency->subunit);
        $this->assertSame($attributes['subunit_to_unit'],     $currency->subunit_to_unit);
        $this->assertSame($attributes['symbol_first'],        $currency->symbol_first);
        $this->assertSame($attributes['decimal_separator'],   $currency->decimal_separator);
        $this->assertSame($attributes['thousands_separator'], $currency->thousands_separator);
    }

    /**
     * Assert the currency entity instance.
     *
     * @param  mixed  $currency
     */
    protected function assertCurrencyEntityInstance($currency)
    {
        $expectations = [
            \Arcanedev\Currencies\Contracts\Entities\Currency::class,
            \Arcanedev\Currencies\Entities\Currency::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $currency);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a currency entity.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    protected function makeCurrency($iso)
    {
        return Currency::make($iso, $this->getCurrencyAttributes($iso));
    }

    /**
     * @param  string  $iso
     *
     * @return array
     */
    protected function getCurrencyAttributes($iso)
    {
        return array_get($this->getCurrenciesAttributes(), $iso);
    }

    /**
     * Get currencies from the config file.
     *
     * @return array
     */
    protected function getCurrenciesFromConfig()
    {
        return $this->app['config']->get('currencies.data', []);
    }

    /**
     * Get the currencies attributes.
     *
     * @return array
     */
    protected function getCurrenciesAttributes()
    {
        return [
            'EUR' => [
                'iso_numeric'         => '978',
                'name'                => 'Euro',
                'symbol'              => '€',
                'alternate_symbols'   => [],
                'subunit'             => 'Cent',
                'subunit_to_unit'     => 100,
                'symbol_first'        => true,
                'html_entity'         => '&#x20AC;',
                'decimal_separator'   => ',',
                'thousands_separator' => '.',
            ],
            'USD' => [
                'iso_numeric'         => '840',
                'name'                => 'United States Dollar',
                'symbol'              => '$',
                'alternate_symbols'   => ['US$'],
                'subunit'             => 'Cent',
                'subunit_to_unit'     => 100,
                'symbol_first'        => true,
                'html_entity'         => '$',
                'decimal_separator'   => '.',
                'thousands_separator' => ',',
            ],
        ];
    }

    /**
     * Get dummy rates for testing.
     * 
     * @return array
     */
    protected function getDummyRates()
    {
        return [
            'USD' => [
                'AED' => 3.673028,
                'AFN' => 68.87,
                'ALL' => 121.7401,
                'AMD' => 476.265002,
                'ANG' => 1.7888,
                'AOA' => 165.893666,
                'ARS' => 14.17537,
                'AUD' => 1.324254,
                'AWG' => 1.793333,
                'AZN' => 1.538675,
                'BAM' => 1.728991,
                'BBD' => 2,
                'BDT' => 78.56374,
                'BGN' => 1.731065,
                'BHD' => 0.377138,
                'BIF' => 1675.622476,
                'BMD' => 1,
                'BND' => 1.345749,
                'BOB' => 6.912068,
                'BRL' => 3.354186,
                'BSD' => 1,
                'BTC' => 0.001591946027,
                'BTN' => 67.380467,
                'BWP' => 10.735563,
                'BYR' => 19820.15,
                'BZD' => 2.016625,
                'CAD' => 1.282715,
                'CDF' => 960.29325,
                'CHF' => 0.960247,
                'CLF' => 0.024602,
                'CLP' => 675.486304,
                'CNY' => 6.580977,
                'COP' => 2938.686696,
                'CRC' => 545.6316,
                'CUC' => 1,
                'CUP' => 24.728383,
                'CVE' => 97.620266,
                'CZK' => 23.96213,
                'DJF' => 177.545001,
                'DKK' => 6.584764,
                'DOP' => 46.017,
                'DZD' => 109.779599,
                'EEK' => 13.85685,
                'EGP' => 8.877957,
                'ERN' => 15.0015,
                'ETB' => 21.82536,
                'EUR' => 0.884403,
                'FJD' => 2.061383,
                'FKP' => 0.686404,
                'GBP' => 0.686404,
                'GEL' => 2.22886,
                'GGP' => 0.686404,
                'GHS' => 3.964002,
                'GIP' => 0.686404,
                'GMD' => 42.87454,
                'GNF' => 8446.124903,
                'GTQ' => 7.655101,
                'GYD' => 206.958836,
                'HKD' => 7.757641,
                'HNL' => 22.80163,
                'HRK' => 6.65668,
                'HTG' => 63.063975,
                'HUF' => 278.2943,
                'IDR' => 13232.75,
                'ILS' => 3.837856,
                'IMP' => 0.686404,
                'INR' => 67.33164,
                'IQD' => 1164.016292,
                'IRR' => 30362,
                'ISK' => 122.049,
                'JEP' => 0.686404,
                'JMD' => 125.881401,
                'JOD' => 0.708428,
                'JPY' => 105.027201,
                'KES' => 101.16445,
                'KGS' => 67.485249,
                'KHR' => 4089.4975,
                'KMF' => 431.33453,
                'KPW' => 900.09,
                'KRW' => 1151.471675,
                'KWD' => 0.300892,
                'KYD' => 0.82636,
                'KZT' => 336.662492,
                'LAK' => 8126.295049,
                'LBP' => 1512.273333,
                'LKR' => 147.028899,
                'LRD' => 90.50905,
                'LSL' => 14.58124,
                'LTL' => 3.029371,
                'LVL' => 0.621793,
                'LYD' => 1.366095,
                'MAD' => 9.675664,
                'MDL' => 19.73746,
                'MGA' => 3291.886667,
                'MKD' => 54.44427,
                'MMK' => 1190.839988,
                'MNT' => 1941.666667,
                'MOP' => 8.011656,
                'MRO' => 358.109166,
                'MTL' => 0.683738,
                'MUR' => 35.308163,
                'MVR' => 15.316667,
                'MWK' => 711.800202,
                'MXN' => 18.44083,
                'MYR' => 4.007186,
                'MZN' => 61.97,
                'NAD' => 14.58124,
                'NGN' => 283.8604,
                'NIO' => 28.64246,
                'NOK' => 8.249358,
                'NPR' => 108.0018,
                'NZD' => 1.386183,
                'OMR' => 0.385017,
                'PAB' => 1,
                'PEN' => 3.292465,
                'PGK' => 3.142375,
                'PHP' => 46.5031,
                'PKR' => 104.950899,
                'PLN' => 3.879402,
                'PYG' => 5669.505,
                'QAR' => 3.640597,
                'RON' => 3.992156,
                'RSD' => 109.626579,
                'RUB' => 63.98843,
                'RWF' => 796.445754,
                'SAR' => 3.750658,
                'SBD' => 7.79857,
                'SCR' => 13.1317,
                'SDG' => 6.10241,
                'SEK' => 8.249164,
                'SGD' => 1.342843,
                'SHP' => 0.686404,
                'SLL' => 3941.6058,
                'SOS' => 587.315494,
                'SRD' => 7.006,
                'STD' => 21712,
                'SVC' => 8.772953,
                'SYP' => 218.912332,
                'SZL' => 14.58414,
                'THB' => 35.23901,
                'TJS' => 7.8685,
                'TMT' => 3.5016,
                'TND' => 2.156606,
                'TOP' => 2.220539,
                'TRY' => 2.877363,
                'TTD' => 6.66551,
                'TWD' => 32.09229,
                'TZS' => 2196.06165,
                'UAH' => 24.91479,
                'UGX' => 3369.336667,
                'USD' => 1,
                'UYU' => 30.70814,
                'UZS' => 2949.354981,
                'VEF' => 9.9715,
                'VND' => 22346.633333,
                'VUV' => 109.350001,
                'WST' => 2.514619,
                'XAF' => 581.638957,
                'XAG' => 0.057532,
                'XAU' => 0.0007895,
                'XCD' => 2.70302,
                'XDR' => 0.704733,
                'XOF' => 581.941157,
                'XPD' => 0.00176,
                'XPF' => 105.49585,
                'XPT' => 0.001035,
                'YER' => 249.269001,
                'ZAR' => 14.59941,
                'ZMK' => 5253.075255,
                'ZMW' => 10.95365,
                'ZWL' => 322.387247,
            ],
        ];
    }

    /**
     * Get dummy rates from a specific currency for testing.
     * 
     * @param  string  $iso
     * 
     * @return array
     */
    protected function getDummyRatesFrom($iso)
    {
        return $this->getDummyRates()[$iso];
    }
}
