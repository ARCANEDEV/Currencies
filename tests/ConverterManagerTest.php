<?php namespace Arcanedev\Currencies\Tests;

/**
 * Class     ConverterManagerTest
 *
 * @package  Arcanedev\Currencies\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ConverterManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Currencies\Contracts\ConverterManager */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app->make('arcanedev.currencies.converter');
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
            \Arcanedev\Currencies\Contracts\ConverterManager::class,
            \Arcanedev\Currencies\ConverterManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_can_get_default_driver()
    {
        $driver       = $this->manager->driver();
        $expectations = [
            \Arcanedev\Currencies\Contracts\Services\CurrencyService::class,
            \Arcanedev\Currencies\Services\AbstractService::class,
            \Arcanedev\Currencies\Services\OpenExchangeRatesService::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $driver);
        }
    }

    /** @test */
    public function it_can_get_multiple_drivers()
    {
        $supported = [
            'openexchangerates' => \Arcanedev\Currencies\Services\OpenExchangeRatesService::class,
            'currencylayer'     => \Arcanedev\Currencies\Services\CurrencyLayerService::class,
        ];

        foreach ($supported as $name => $serviceClass) {
            $driver       = $this->manager->driver($name);
            $expectations = [
                \Arcanedev\Currencies\Contracts\Services\CurrencyService::class,
                \Arcanedev\Currencies\Services\AbstractService::class,
                $serviceClass
            ];

            foreach ($expectations as $expected) {
                $this->assertInstanceOf($expected, $driver);
            }
        }
    }
}
