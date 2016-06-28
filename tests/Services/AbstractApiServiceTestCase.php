<?php namespace Arcanedev\Currencies\Tests\Services;

use Arcanedev\Currencies\Tests\TestCase;
use Prophecy\Argument;

/**
 * Class     AbstractApiServiceTestCase
 *
 * @package  Arcanedev\Currencies\Tests\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractApiServiceTestCase extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Currencies\Contracts\Services\CurrencyService */
    protected $converter;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->converter = $this->makeConverter();
    }

    public function tearDown()
    {
        unset($this->converter);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Commons Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the service key.
     *
     * @return string
     */
    abstract protected function getServiceKey();

    /**
     * Get the service class.
     *
     * @return string
     */
    abstract protected function getServiceClass();

    /**
     * Get the service configs.
     *
     * @return array
     */
    abstract protected function getServiceConfig();

    /**
     * Get API rates from the json file.
     *
     * @return array
     */
    abstract protected function getApiRates();

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\Currencies\Contracts\Services\CurrencyService::class,
            \Arcanedev\Currencies\Bases\AbstractService::class,
            \Arcanedev\Currencies\Services\AbstractApiService::class,
            $this->getServiceClass(),
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->converter);
        }
    }

    /** @test */
    public function it_can_get_supported_rates()
    {
        $default        = $this->converter->getDefault();
        $supported      = $this->converter->getSupported();
        $supportedRates = $this->converter->supportedRates();

        $this->assertCount(count($supported), $supportedRates);
        $this->assertSame($default, $supportedRates->getFrom());

        foreach ($supported as $iso) {
            $this->assertTrue($supportedRates->has($iso));

            $rate = $supportedRates->get($iso);

            $this->assertInstanceOf(\Arcanedev\Currencies\Contracts\Entities\Rate::class, $rate);
            $this->assertSame($default, $rate->from());
            $this->assertSame($iso,     $rate->to());
        }
    }

    /** @test */
    public function it_can_get_all_rates()
    {
        $rates          = $this->getApiRates();
        $rateCollection = $this->converter->rates();

        $this->assertCount(count($rates), $rateCollection);

        foreach ($rates as $iso => $ratio) {
            $this->assertTrue($rateCollection->has($iso));
            /** @var \Arcanedev\Currencies\Entities\Rate $rate */
            $rate = $rateCollection->get($iso);
            $this->assertSame($ratio, $rate->ratio());
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the converter.
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    protected function makeConverter()
    {
        $class = $this->getServiceClass();
        $client = $this->mockHttpClient();

        return new $class(
            $this->app[\Arcanedev\Currencies\Contracts\CurrencyManager::class],
            $this->app[\Illuminate\Contracts\Cache\Repository::class],
            $this->getServiceConfig(),
            $client->reveal()
        );
    }

    /**
     * Mock the http client.
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function mockHttpClient()
    {
        $response = $this->getApiResponseFixture();
        $client   = $this->prophesize(\Arcanedev\Currencies\Contracts\Http\Client::class);

        $client->get(Argument::type('string'), Argument::type('array'))->willReturn($response);
        $client->setBaseUrl(Argument::type('string'))->shouldBeCalled();

        return $client;
    }

    protected function getApiResponseFixture($toArray = false)
    {
        $path     = __DIR__ . '/../fixtures/api/' . $this->getServiceKey() . '.json';
        $response = file_get_contents(realpath($path));

        return $toArray ? json_decode($response, true) : $response;
    }
}
