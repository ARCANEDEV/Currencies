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
        $this->assertCurrencyEntityInstance($this->currency);
    }

    /** @test */
    public function it_can_access_the_attributes()
    {
        foreach ($this->getCurrenciesAttributes() as $key => $attributes) {
            $this->assertCurrencyEntity($key, $attributes, new Currency($key, $attributes));
        }
    }

    /** @test */
    public function it_can_make()
    {
        foreach ($this->getCurrenciesAttributes() as $key => $attributes) {
            $this->assertCurrencyEntity($key, $attributes, Currency::make($key, $attributes));
        }
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        foreach ($this->getCurrenciesAttributes() as $key => $attributes) {
            $currency   = Currency::make($key, $attributes)->toArray();
            $attributes = compact('key') + $attributes;

            foreach ($attributes as $k => $v) {
                $this->assertArrayHasKey($k, $currency);
                $this->assertSame($v, $currency[$k]);
            }
        }
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        foreach ($this->getCurrenciesAttributes() as $key => $attributes) {
            $this->currency = new Currency($key, $attributes);
            $attributes     = compact('key') + $attributes;

            $this->assertJson($this->currency->toJson());
            $this->assertSame(json_encode($attributes), $this->currency->toJson());
        }
    }

    /** @test */
    public function it_can_format()
    {
        $expectations = [
            'USD' => [
                [
                    'amount'   => 50000,
                    'expected' => '$ 500.00'
                ],[
                    'amount'   => 100000,
                    'expected' => '$ 1,000.00'
                ]
            ],
            'EUR' => [
                [
                    'amount'   => 50000,
                    'expected' => '€ 500,00'
                ],[
                    'amount'   => 100000,
                    'expected' => '€ 1.000,00'
                ]
            ],
        ];

        foreach ($expectations as $iso => $expectation) {
            $this->currency = $this->makeCurrency($iso);

            foreach ($expectation as $data) {
                $this->assertSame($data['expected'], $this->currency->format($data['amount']));
            }
        }
    }

    /** @test */
    public function it_can_access_currency_as_an_array()
    {
        foreach ($this->getCurrenciesAttributes() as $key => $attributes) {
            $this->currency = Currency::make($key, $attributes);

            foreach ($attributes as $k => $v) {
                $this->assertArrayHasKey($k, $this->currency);
                $this->assertSame($v, $this->currency[$k]);

                // Check does not effect the object
                $this->currency[$k] = null;
                $this->assertSame($v, $this->currency[$k]);

                unset($this->currency[$k]);
                $this->assertArrayHasKey($k, $this->currency);
                $this->assertSame($v, $this->currency[$k]);
            }
        }
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Currencies\Exceptions\InvalidCurrencyArgumentException
     * @expectedExceptionMessage  The Currency attributes are missing: [name, symbol, alternate_symbols, subunit, subunit_to_unit, symbol_first, html_entity]
     */
    public function it_must_throw_an_invalid_currency_argument_exception_on_missing_attributes()
    {
        Currency::make('ZZZ', [
            'iso_numeric'         => '999',
            'decimal_separator'   => ',',
            'thousands_separator' => '.',
        ]);
    }
}
