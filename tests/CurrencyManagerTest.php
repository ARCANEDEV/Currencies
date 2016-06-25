<?php namespace Arcanedev\Currencies\Tests;

/**
 * Class     CurrencyManagerTest
 *
 * @package  Arcanedev\Currencies\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\CurrencyManager */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app->make('arcanedev.currencies.manager');
    }

    public function tearDown()
    {
        unset($this->manager);

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
            \Arcanedev\Currencies\Contracts\CurrencyManager::class,
            \Arcanedev\Currencies\CurrencyManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_can_get_all_currencies()
    {
        $currencies = $this->manager->currencies();

        $this->assertInstanceOf(\Arcanedev\Currencies\Entities\CurrencyCollection::class, $currencies);
        $this->assertFalse($currencies->isEmpty());
    }

    /** @test */
    public function it_can_get_default_currency()
    {
        $default         = $this->manager->getDefault();
        $defaultCurrency = $this->manager->getDefaultCurrency();

        $this->assertInstanceOf(\Arcanedev\Currencies\Entities\Currency::class, $defaultCurrency);
        $this->assertSame($default, $defaultCurrency->key);
    }

    /** @test */
    public function it_can_get_supported_currencies()
    {
        $supported           = $this->manager->getSupported();
        $supportedCurrencies = $this->manager->getSupportedCurrencies();

        $this->assertInstanceOf(\Arcanedev\Currencies\Entities\CurrencyCollection::class, $supportedCurrencies);
        $this->assertFalse($supportedCurrencies->isEmpty());
        $this->assertCount(count($supported), $supportedCurrencies);

        foreach ($supported as $iso) {
            $this->assertTrue($supportedCurrencies->has($iso));
            $this->assertSame($iso, $supportedCurrencies->get($iso)->key);
        }
    }

    /** @test */
    public function it_can_get_a_currency_from_collection()
    {
        $supported = $this->manager->getSupported();

        foreach ($supported as $iso) {
            $currency = $this->manager->get($iso);

            $this->assertInstanceOf(\Arcanedev\Currencies\Entities\Currency::class, $currency);
            $this->assertSame($iso, $currency->key);
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
        $this->manager->findOrFail('ZZZ');
    }

    /** @test */
    public function it_can_get_currency_symbol()
    {
        $currencies = $this->manager->currencies();

        foreach ($currencies as $iso => $currency) {
            $this->assertSame($currency->symbol, $this->manager->symbol($iso));
        }
    }
}
