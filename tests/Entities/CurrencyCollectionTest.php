<?php namespace Arcanedev\Currencies\Tests\Entities;

use Arcanedev\Currencies\Entities\CurrencyCollection;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     CurrencyCollectionTest
 *
 * @package  Arcanedev\Currencies\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyCollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Entities\CurrencyCollection */
    private $currencies;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->currencies = new CurrencyCollection;
    }

    public function tearDown()
    {
        unset($this->currencies);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\Collection::class,
            \Arcanedev\Support\Collection::class,
            \Arcanedev\Currencies\Entities\CurrencyCollection::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->currencies);
        }

        $this->assertCount(0, $this->currencies);
    }

    /** @test */
    public function it_can_make()
    {
        $this->currencies = CurrencyCollection::make();

        $expectations = [
            \Illuminate\Support\Collection::class,
            \Arcanedev\Support\Collection::class,
            \Arcanedev\Currencies\Entities\CurrencyCollection::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->currencies);
        }

        $this->assertCount(0, $this->currencies);
    }

    /** @test */
    public function it_can_add_raw_currencies()
    {
        $count = 0;

        $this->assertTrue($this->currencies->isEmpty());

        foreach ($this->getCurrenciesAttributes() as $iso => $attributes) {
            $this->currencies->add($iso, $attributes);
            $count++;

            $this->assertCount($count, $this->currencies);

            $currency = $this->currencies->get($iso);

            $this->assertCurrencyEntity($iso, $attributes, $currency);
        }

        $this->assertFalse($this->currencies->isEmpty());
    }

    /** @test */
    public function it_can_load_many_currencies()
    {
        $this->assertTrue($this->currencies->isEmpty());

        $currencies = $this->loadAllCurrencies(false);

        $this->assertFalse($this->currencies->isEmpty());
        $this->assertCount(count($currencies['iso']), $this->currencies); // Only iso currencies

        $currencies = $this->loadAllCurrencies();

        $this->assertFalse($this->currencies->isEmpty());
        $this->assertCount(count(array_collapse($currencies)), $this->currencies);
    }

    /** @test */
    public function it_can_get_a_currency()
    {
        $currencies = $this->loadAllCurrencies();

        foreach (array_collapse($currencies) as $iso => $attributes) {
            $this->assertCurrencyEntity($iso, $attributes, $this->currencies->findOrFails($iso));
        }
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     * @expectedExceptionMessage  The Currency with the ISO code [ZZZ] not found
     */
    public function it_must_throw_a_currency_not_found_exception()
    {
        $this->currencies->findOrFails('ZZZ');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load all currencies.
     *
     * @param  bool  $includeNonIso
     *
     * @return array
     */
    private function loadAllCurrencies($includeNonIso = true)
    {
        $currencies = $this->getCurrenciesFromConfig();

        $this->currencies->load($currencies, $includeNonIso);

        return $currencies;
    }
}
