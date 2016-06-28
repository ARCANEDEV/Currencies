<?php namespace Arcanedev\Currencies\Bases;

use Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
use Arcanedev\Currencies\Contracts\Entities\Rate as RateContract;
use Arcanedev\Currencies\Contracts\Services\CurrencyService;
use Arcanedev\Currencies\Entities\RateCollection;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\Arr;

/**
 * Class     AbstractService
 *
 * @package  Arcanedev\Currencies\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractService implements CurrencyService
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The currency manager instance.
     *
     * @var \Arcanedev\Currencies\Contracts\CurrencyManager
     */
    protected $manager;

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
     * AbstractApiService constructor.
     *
     * @param  \Arcanedev\Currencies\Contracts\CurrencyManager  $manager
     * @param  \Illuminate\Contracts\Cache\Repository           $cache
     * @param  array                                            $configs
     */
    public function __construct(
        CurrencyManagerContract $manager,
        CacheContract $cache,
        array $configs
    ) {
        $this->manager = $manager;
        $this->cache   = $cache;
        $this->setConfigs($configs);
        $this->setCacheConfigs($configs);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array  $configs
     */
    abstract protected function setConfigs(array $configs);

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
        return is_null($from) ? $this->getDefault() : $from;
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
     * Cache results.
     *
     * @param  \Closure  $callback
     *
     * @return array
     */
    protected function cacheResults(Closure $callback)
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
    protected function makeRatesCollection($from, array $rates)
    {
        return RateCollection::make()->load($from, $rates);
    }
}
