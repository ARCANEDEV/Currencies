<?php namespace Arcanedev\Currencies;

use Arcanedev\Currencies\Contracts\ConverterManager as ConverterManagerContract;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\Manager;

/**
 * Class     ConverterManager
 *
 * @package  Arcanedev\Currencies
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ConverterManager extends Manager implements ConverterManagerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config()->get('currencies.converters.default');
    }

    /**
     * Create an instance of Openexchangerates service.
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    protected function createOpenexchangeratesDriver()
    {
        return $this->buildApiProvider(
            Services\OpenExchangeRatesService::class,
            $this->getProviderConfigs('openexchangerates')
        );
    }

    /**
     * Create an instance of Currencylayer service.
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    protected function createCurrencylayerDriver()
    {
        return $this->buildApiProvider(
            Services\CurrencyLayerService::class,
            $this->getProviderConfigs('currencylayer')
        );
    }

    /**
     * Create an instance of Array store.
     *
     * @return \Arcanedev\Currencies\Stores\ArrayStore
     */
    protected function createArrayDriver()
    {
        return new Stores\ArrayStore(
            $this->app[Contracts\CurrencyManager::class],
            $this->app[CacheContract::class],
            $this->getProviderConfigs('array')
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the config repository instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    protected function config()
    {
        return $this->app['config'];
    }

    /**
     * Get the provider configs.
     *
     * @param  string  $provider
     *
     * @return array
     */
    protected function getProviderConfigs($provider)
    {
        $configs          = $this->config()->get("currencies.converters.providers.$provider");
        $configs['cache'] = $this->config()->get("currencies.converters.cache");

        return $configs;
    }

    /**
     * Build the converter provider.
     *
     * @param  string  $provider
     * @param  array   $configs
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    protected function buildApiProvider($provider, array $configs)
    {
        return new $provider(
            $this->app[Contracts\CurrencyManager::class],
            $this->app[CacheContract::class],
            $configs,
            $this->app[Contracts\Http\Client::class]
        );
    }
}
