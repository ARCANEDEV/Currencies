<?php namespace Arcanedev\Currencies\Tests\Services;

use Arcanedev\Currencies\Services\OpenExchangeRatesService;
use Arcanedev\Currencies\Tests\TestCase;
use Prophecy\Argument;

/**
 * Class     OpenExchangeRatesConverterTest
 *
 * @package  Arcanedev\Currencies\Tests\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OpenExchangeRatesConverterTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    protected $converter;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $client = $this->mockHttpClient();
        $this->converter = new OpenExchangeRatesService(
            $this->app[\Arcanedev\Currencies\Contracts\CurrencyManager::class],
            $client->reveal(),
            $this->app[\Illuminate\Contracts\Cache\Repository::class],
            ['api-id' => 'YOUR_API_ID_HERE']
        );
    }

    public function tearDown()
    {
        unset($this->converter);

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
            \Arcanedev\Currencies\Contracts\Services\CurrencyService::class,
            \Arcanedev\Currencies\Services\AbstractService::class,
            \Arcanedev\Currencies\Services\OpenExchangeRatesService::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->converter);
        }
    }

    /** @test */
    public function it_can_get_all_rates()
    {
        $dummyRates     = $this->getDummyRatesFrom('USD');
        $rateCollection = $this->converter->rates();

        $this->assertCount(count($dummyRates), $rateCollection);

        foreach ($dummyRates as $iso => $ratio) {
            $this->assertTrue($rateCollection->has($iso));
            /** @var \Arcanedev\Currencies\Entities\Rate $rate */
            $rate = $rateCollection->get($iso);
            $this->assertSame($ratio, $rate->ratio());
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function mockHttpClient()
    {
        $client = $this->prophesize(\Arcanedev\Currencies\Contracts\Http\Client::class);
        $client->get(Argument::type('string'), Argument::type('array'))
            ->willReturn(json_encode([
                'rates' => $this->getDummyRatesFrom('USD'),
            ]));
        $client->setBaseUrl(Argument::type('string'))->shouldBeCalled();

        return $client;
    }
}
