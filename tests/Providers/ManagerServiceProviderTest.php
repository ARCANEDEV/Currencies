<?php namespace Arcanedev\Currencies\Tests\Providers;

use Arcanedev\Currencies\Providers\ManagerServiceProvider;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     ManagerServiceProviderTest
 *
 * @package  Arcanedev\Currencies\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ManagerServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Providers\ManagerServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(ManagerServiceProvider::class);
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
            ManagerServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            'arcanedev.currencies.http-client',
            \Arcanedev\Currencies\Contracts\Http\Client::class,
            'arcanedev.currencies.manager',
            \Arcanedev\Currencies\Contracts\CurrencyManager::class,
            'arcanedev.currencies.converter',
            \Arcanedev\Currencies\Contracts\ConverterManager::class,
        ];

        $this->assertSame($expected, $this->provider->provides());
    }
}
