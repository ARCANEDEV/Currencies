<?php namespace Arcanedev\Currencies\Tests;

use Arcanedev\Currencies\CurrenciesServiceProvider;

/**
 * Class     CurrenciesServiceProviderTest
 *
 * @package  Arcanedev\Currencies\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrenciesServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\CurrenciesServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(CurrenciesServiceProvider::class);
    }

    public function tearDown()
    {
        unset($this->provider);

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
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            \Arcanedev\Support\PackageServiceProvider::class,
            \Arcanedev\Currencies\CurrenciesServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            'arcanedev.currencies.manager',
            \Arcanedev\Currencies\Contracts\CurrencyManager::class,
        ];

        $this->assertSame($expected, $this->provider->provides());
    }
}
