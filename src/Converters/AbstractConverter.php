<?php namespace Arcanedev\Currencies\Converters;

use Arcanedev\Currencies\Contracts\CurrencyConverter;
use Arcanedev\Currencies\Contracts\Http\Client as ClientContract;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\Arr;

/**
 * Class     AbstractConverter
 *
 * @package  Arcanedev\Currencies\Converters
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractConverter implements CurrencyConverter
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
     * AbstractConverter constructor.
     *
     * @param  \Arcanedev\Currencies\Contracts\Http\Client  $client
     * @param  \Illuminate\Contracts\Cache\Repository       $cache
     * @param  array                                        $configs
     */
    public function __construct(ClientContract $client, CacheContract $cache, array $configs = [])
    {
        $this->client = $client;
        $this->cache  = $cache;
        $this->setCacheConfigs($configs);
        $this->setProviderConfigs($configs);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get the default currency.
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return config('currencies.default', 'USD');
    }

    /**
     * Get the supported currencies.
     *
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return config('currencies.supported', []);
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
     *
     * @return mixed
     */
    abstract protected function setProviderConfigs(array $configs);

    /**
     * Check if cache is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled()
    {
        return (bool) $this->cacheEnabled;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
}
