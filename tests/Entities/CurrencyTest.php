<?php namespace Arcanedev\Currencies\Tests\Entities;

use Arcanedev\Currencies\Entities\Currency;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     CurrencyTest
 *
 * @package  Arcanedev\Currencies\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Entities\Currency */
    private $currency;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->currency = new Currency('USD', $this->getCurrencyAttributes('USD'));
    }

    public function tearDown()
    {
        unset($this->currency);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Currency::class, $this->currency);
    }

    /** @test */
    public function it_can_access_the_attributes()
    {
        foreach ($this->getCurrencies() as $key => $attributes) {
            $this->currency = new Currency($key, $attributes);

            $this->assertSame($key,                               $this->currency->key);
            $this->assertSame($attributes['iso_numeric'],         $this->currency->iso_numeric);
            $this->assertSame($attributes['name'],                $this->currency->name);
            $this->assertSame($attributes['symbol'],              $this->currency->symbol);
            $this->assertSame($attributes['alternate_symbols'],   $this->currency->alternate_symbols);
            $this->assertSame($attributes['subunit'],             $this->currency->subunit);
            $this->assertSame($attributes['subunit_to_unit'],     $this->currency->subunit_to_unit);
            $this->assertSame($attributes['symbol_first'],        $this->currency->symbol_first);
            $this->assertSame($attributes['decimal_separator'],   $this->currency->decimal_separator);
            $this->assertSame($attributes['thousands_separator'], $this->currency->thousands_separator);
        }
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        foreach ($this->getCurrencies() as $key => $attributes) {
            $this->currency = new Currency($key, $attributes);
            $attributes     = compact('key') + $attributes;

            $this->assertJson($this->currency->toJson());
            $this->assertSame(json_encode($attributes), $this->currency->toJson());
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param  string  $iso
     *
     * @return array
     */
    private function getCurrencyAttributes($iso)
    {
        return array_get($this->getCurrencies(), $iso);
    }

    /**
     * @return array
     */
    private function getCurrencies()
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
}
