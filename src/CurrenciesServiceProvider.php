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
     * Package name.
     *
     * @var string
     */
    protected $package = 'currencies';

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
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->register(Providers\ManagerServiceProvider::class);
        $this->app->register(Providers\ValidationServiceProvider::class);
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        // Publishes
        $this->publishConfig();
        $this->publishTranslations();
    }
}
