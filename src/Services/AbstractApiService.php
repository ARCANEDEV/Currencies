<?php namespace Arcanedev\Currencies\Services;

use Arcanedev\Currencies\Bases\AbstractService;
use Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
use Arcanedev\Currencies\Contracts\Http\Client as ClientContract;
use Illuminate\Contracts\Cache\Repository as CacheContract;

/**
 * Class     AbstractApiService
 *
 * @package  Arcanedev\Currencies\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractApiService extends AbstractService
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The base URL.
     *
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @var \Arcanedev\Currencies\Contracts\Http\Client
     */
    protected $client;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * AbstractApiService constructor.
     *
     * @param  \Arcanedev\Currencies\Contracts\CurrencyManager  $manager
     * @param  \Arcanedev\Currencies\Contracts\Http\Client      $client
     * @param  \Illuminate\Contracts\Cache\Repository           $cache
     * @param  array                                            $configs
     */
    public function __construct(
        CurrencyManagerContract $manager,
        CacheContract $cache,
        array $configs,
        ClientContract $client
    ) {
        $this->client  = $client;
        $this->client->setBaseUrl($this->baseUrl);

        parent::__construct($manager, $cache, $configs);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get currencies rates.
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function rates()
    {
        $from     = $this->getFromCurrency();
        $self     = $this;
        $callback = function () use ($self, $from) {
            return $self->request($from);
        };

        $rates = $this->isCacheEnabled()
            ? $this->cacheResults($callback)
            : call_user_func($callback);

        return $this->makeRatesCollection($from, $rates);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make an API request.
     *
     * @param  string  $from
     * @param  array   $to
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    abstract protected function request($from, array $to = []);
}
