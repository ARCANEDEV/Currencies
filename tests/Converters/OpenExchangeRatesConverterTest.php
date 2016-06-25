<?php namespace Arcanedev\Currencies\Tests\Converters;

use Arcanedev\Currencies\Converters\OpenExchangeRatesConverter;
use Arcanedev\Currencies\Tests\TestCase;
use Prophecy\Argument;

/**
 * Class     OpenExchangeRatesConverterTest
 *
 * @package  Arcanedev\Currencies\Tests\Converters
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OpenExchangeRatesConverterTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var \Arcanedev\Currencies\Contracts\CurrencyConverter
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
        $this->converter = new OpenExchangeRatesConverter(
            $client->reveal(),
            $this->app['cache']->driver(),
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
            \Arcanedev\Currencies\Contracts\CurrencyConverter::class,
            \Arcanedev\Currencies\Converters\AbstractConverter::class,
            \Arcanedev\Currencies\Converters\OpenExchangeRatesConverter::class,
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function mockHttpClient()
    {
        $client = $this->prophesize(\Arcanedev\Currencies\Contracts\Http\Client::class);
        $client->get(Argument::type('string'))
            ->willReturn(json_encode([
                'rates' => $this->getDummyRatesFrom('USD'),
            ]));

        return $client;
    }
}
