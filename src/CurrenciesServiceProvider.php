<?php namespace Arcanedev\Currencies;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     CurrenciesServiceProvider
 *
 * @package  Arcanedev\Currencies
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrenciesServiceProvider extends PackageServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor  = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'currencies';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerCurrencyManager();
    }

    public function boot()
    {
        parent::boot();

        $this->loadCurrencies();

        // Publishes
        $this->publishConfig();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.currencies.manager',
            Contracts\CurrencyManager::class,
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

            return new CurrencyManager(
                $config->get('currencies')
            );
        });

        $this->app->bind(Contracts\CurrencyManager::class, 'arcanedev.currencies.manager');
    }

    private function loadCurrencies()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config  = $this->app['config'];

        /** @var CurrencyManager $manager */
        $manager = $this->app->make('arcanedev.currencies.manager');
        $manager->load($config->get('currencies.data', []));
    }
}
