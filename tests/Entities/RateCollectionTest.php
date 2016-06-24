<?php namespace Arcanedev\Currencies\Tests\Entities;

use Arcanedev\Currencies\Entities\RateCollection;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     RateCollectionTest
 *
 * @package  Arcanedev\Currencies\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RateCollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Entities\RateCollection */
    private $rates;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->rates = RateCollection::make();
    }

    public function tearDown()
    {
        unset($this->rates);

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
            \Arcanedev\Currencies\Entities\RateCollection::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->rates);
        }
    }

    /** @test */
    public function it_can_load_rates()
    {
        foreach ($this->getDummyRates() as $from => $rates) {
            $this->rates = new RateCollection;

            $this->assertTrue($this->rates->isEmpty());

            $this->rates->load($from, $rates);

            $this->assertFalse($this->rates->isEmpty());
            $this->assertSame($from, $this->rates->getFrom());
            $this->assertCount(count($rates), $this->rates);

            foreach ($rates as $to => $ratio) {
                $this->assertTrue($this->rates->has($to));

                $rate = $this->rates->get($to);

                $this->assertInstanceOf(\Arcanedev\Currencies\Entities\Rate::class, $rate);
                $this->assertSame($from,  $rate->from());
                $this->assertSame($to,    $rate->to());
                $this->assertSame($ratio, $rate->ratio());
            }
        }
    }
}
