<?php namespace Arcanedev\Currencies\Tests\Entities;

use Arcanedev\Currencies\Entities\Rate;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     RateTest
 *
 * @package  Arcanedev\Currencies\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RateTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Entities\Rate */
    private $rate;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->rate = $this->createRateObject('USD', 'EUR');
    }

    public function tearDown()
    {
        unset($rate);

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
            \Arcanedev\Currencies\Contracts\Entities\Rate::class,
            \Arcanedev\Currencies\Entities\Rate::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->rate);
        }
    }

    /** @test */
    public function it_can_make_and_get_attributes()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            foreach ($rates as $to => $ratio) {
                $this->rate = Rate::make($from, $to, $ratio);

                $this->assertSame($from,  $this->rate->from());
                $this->assertSame($to,    $this->rate->to());
                $this->assertSame($ratio, $this->rate->ratio());
            }
        }
    }

    /** @test */
    public function it_can_make_and_get_currencies()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            foreach ($rates as $to => $ratio) {
                $this->rate   = Rate::make($from, $to, $ratio);
                $fromCurrency = $this->rate->getFromCurrency();
                $toCurrency   = $this->rate->getToCurrency();

                $this->assertInstanceOf(\Arcanedev\Currencies\Entities\Currency::class, $fromCurrency);
                $this->assertInstanceOf(\Arcanedev\Currencies\Entities\Currency::class, $toCurrency);
                $this->assertSame($from, $fromCurrency->key);
                $this->assertSame($to,   $toCurrency->key);
            }
        }
    }

    /** @test */
    public function it_can_convert_the_amount()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            foreach ($rates as $to => $ratio) {
                $this->rate   = Rate::make($from, $to, $ratio);

                foreach ([1, 100, 100000] as $amount) {
                    $this->assertSame($amount * $ratio, $this->rate->convert($amount));
                }
            }
        }
    }

    /** @test */
    public function it_can_convert_rate_to_array()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            foreach ($rates as $to => $ratio) {
                $this->rate = Rate::make($from, $to, $ratio);
                $rateArray  = $this->rate->toArray();

                $this->assertInternalType('array', $rateArray);
                $this->assertArrayHasKey('from',   $rateArray);
                $this->assertArrayHasKey('to',     $rateArray);
                $this->assertArrayHasKey('ratio',  $rateArray);

                $this->assertSame($from,  $rateArray['from']);
                $this->assertSame($to,    $rateArray['to']);
                $this->assertSame($ratio, $rateArray['ratio']);
            }
        }
    }

    /** @test */
    public function it_can_convert_rate_to_json()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            foreach ($rates as $to => $ratio) {
                $this->rate = Rate::make($from, $to, $ratio);
                $rateJson   = $this->rate->toJson();

                $this->assertJson($rateJson);
                $this->assertSame(json_encode(compact('from', 'to', 'ratio')), $rateJson);
            }
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a rate object.
     *
     * @param  string  $from
     * @param  string  $to
     *
     * @return \Arcanedev\Currencies\Entities\Rate
     */
    protected function createRateObject($from, $to)
    {
        $rates = $this->getDummyRatesFrom($from);

        return new Rate($from, $to, $rates[$to]);
    }
}
