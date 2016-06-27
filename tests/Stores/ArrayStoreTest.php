<?php namespace Arcanedev\Currencies\Tests\Stores;

use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     ArrayStoreTest
 *
 * @package  Arcanedev\Currencies\Tests\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrayStoreTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Bases\AbstractService */
    protected $store;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        /** @var \Arcanedev\Currencies\Contracts\ConverterManager $converter */
        $converter   = $this->app[\Arcanedev\Currencies\Contracts\ConverterManager::class];
        $this->store = $converter->driver('array');
    }

    public function tearDown()
    {
        unset($this->store);

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
            \Arcanedev\Currencies\Bases\AbstractService::class,
            \Arcanedev\Currencies\Stores\ArrayStore::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->store);
        }
    }

    /** @test */
    public function it_can_get_all_rates()
    {
        $rates     = $this->store->rates();
        $default   = $this->config()->get('currencies.default');
        $supported = $this->config()->get('currencies.supported');

        $this->assertCount(2, $rates);
        $this->assertSame($default, $rates->getFrom());

        foreach ($supported as $iso) {
            $this->assertTrue($rates->has($iso));
            /** @var \Arcanedev\Currencies\Contracts\Entities\Rate $rate */
            $rate = $rates->get($iso);

            $this->assertSame($default, $rate->from());
            $this->assertSame($iso, $rate->to());
        }
    }

    /**
     * Get the config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    private function config()
    {
        return $this->app['config'];
    }
}
