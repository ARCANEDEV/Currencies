<?php namespace Arcanedev\Currencies\Providers;

use Arcanedev\Currencies\Contracts\ConverterManager as ConverterManagerContract;
use Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
use Arcanedev\Currencies\Contracts\Http\Client as HttpClientContract;
use Arcanedev\Currencies\ConverterManager;
use Arcanedev\Currencies\CurrencyManager;
use Arcanedev\Support\ServiceProvider;

/**
 * Class     ManagerServiceProvider
 *
 * @package  Arcanedev\Currencies\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ManagerServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerHttpClient();
        $this->registerCurrencyManager();
        $this->registerCurrencyConverter();
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->loadCurrencies();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.currencies.http-client',
            HttpClientContract::class,
            'arcanedev.currencies.manager',
            CurrencyManagerContract::class,
            'arcanedev.currencies.converter',
            ConverterManagerContract::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function registerCurrencyManager()
    {
        $this->singleton('arcanedev.currencies.manager', function ($app) {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config  = $app['config'];

            return new CurrencyManager($config->get('currencies'));
        });

        $this->app->bind(CurrencyManagerContract::class, 'arcanedev.currencies.manager');
    }

    private function registerCurrencyConverter()
    {
        $this->singleton('arcanedev.currencies.converter', function ($app) {
            return new ConverterManager($app);
        });

        $this->app->bind(ConverterManagerContract::class, 'arcanedev.currencies.converter');
    }

    private function registerHttpClient()
    {
        $this->bind('arcanedev.currencies.http-client', function () {
            return new \Arcanedev\Currencies\Http\Client;
        });

        $this->bind(HttpClientContract::class, 'arcanedev.currencies.http-client');
    }

    private function loadCurrencies()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config  = $this->app['config'];

        /** @var CurrencyManagerContract $manager */
        $manager = $this->app->make('arcanedev.currencies.manager');
        $manager->load($config->get('currencies.data', []));
    }
}
