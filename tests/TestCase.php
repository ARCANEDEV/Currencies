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
     * Get currencies from the config file.
     *
     * @return array
     */
    protected function getCurrenciesFromConfig()
    {
        return $this->app['config']->get('currencies.data', []);
    }
}
