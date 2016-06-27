<?php namespace Arcanedev\Currencies\Services;

use Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
use Arcanedev\Currencies\Contracts\Entities\Rate as RateContract;
use Arcanedev\Currencies\Contracts\Http\Client as ClientContract;
use Arcanedev\Currencies\Contracts\Services\CurrencyService;
use Arcanedev\Currencies\Entities\RateCollection;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\Arr;

/**
 * Class     AbstractService
 *
 * @package  Arcanedev\Currencies\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractService implements CurrencyService
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
     * @var \Arcanedev\Currencies\Contracts\CurrencyManager
     */
    protected $manager;

    /**
     * @var \Arcanedev\Currencies\Contracts\Http\Client
     */
    protected $client;

    /**
     * The cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The enabled cache status.
     *
     * @var bool
     */
    protected $cacheEnabled;

    /**
     * The cache key name.
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * The cache duration.
     *
     * @var int
     */
    protected $cacheDuration;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * AbstractService constructor.
     *
     * @param  \Arcanedev\Currencies\Contracts\CurrencyManager  $manager
     * @param  \Arcanedev\Currencies\Contracts\Http\Client      $client
     * @param  \Illuminate\Contracts\Cache\Repository           $cache
     * @param  array                                            $configs
     */
    public function __construct(
        CurrencyManagerContract $manager,
        ClientContract $client,
        CacheContract $cache,
        array $configs = []
    ) {
        $this->manager = $manager;
        $this->client  = $client;
        $this->cache   = $cache;
        $this->setCacheConfigs($configs);
        $this->setProviderConfigs($configs);

        $this->client->setBaseUrl($this->baseUrl);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default currency.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->manager->getDefault();
    }

    /**
     * Get the supported currencies.
     *
     * @return array
     */
    public function getSupported()
    {
        return $this->manager->getSupported();
    }

    /**
     * Get the `from` currency.
     *
     * @param  string|null  $from
     *
     * @return string
     */
    protected function getFromCurrency($from = null)
    {
        if (is_null($from)) {
            $from = $this->getDefault();
        }

        return $from;
    }

    /**
     * Get the `to` currencies.
     *
     * @param  array|string|null  $to
     *
     * @return array
     */
    protected function getToCurrencies($to = null)
    {
        if (is_null($to)) {
            return array_diff($this->getSupported(), [$this->getDefault()]);
        }

        return is_array($to) ? $to : [$to];
    }

    /**
     * Set the cache configs.
     *
     * @param  array  $configs
     */
    protected function setCacheConfigs(array $configs)
    {
        $this->cacheEnabled  = Arr::get($configs, 'cache.enabled', false);
        $this->cacheKey      = Arr::get($configs, 'cache.key', 'currencies.rates');
        $this->cacheDuration = Arr::get($configs, 'cache.duration', 0);
    }

    /**
     * Set the configs.
     *
     * @param  array  $configs
     */
    abstract protected function setProviderConfigs(array $configs);

    /**
     * Get cache key.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Check if cache is enabled.
     *
     * @return bool
     */
    protected function isCacheEnabled()
    {
        return (bool) $this->cacheEnabled;
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

        return $this->prepareRates($from, $rates);
    }

    /**
     * Get supported currencies rates.
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function supportedRates()
    {
        $supported = $this->getSupported();
        $rates     = $this->rates();
        $from      = $rates->getFrom();

        return $rates->filter(function (RateContract $rate) use ($supported) {
            return in_array($rate->to(), $supported);
        })->setFrom($from);
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

    /**
     * Cache results.
     *
     * @param  Closure  $callback
     *
     * @return array
     */
    private function cacheResults(Closure $callback)
    {
        return $this->cache->remember(
            $this->getCacheKey(),
            $this->cacheDuration,
            $callback
        );
    }

    /**
     * Prepare rates collection.
     *
     * @param  string  $from
     * @param  array   $rates
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    protected function prepareRates($from, array $rates)
    {
        return RateCollection::make()->load($from, $rates);
    }
}
