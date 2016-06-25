<?php namespace Arcanedev\Currencies\Validators;

use Arcanedev\Support\ServiceProvider;

/**
 * Class     ValidationServiceProvider
 *
 * @package  Arcanedev\Currencies\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValidationServiceProvider extends ServiceProvider
{
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
        //
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        /** @var \Illuminate\Validation\Factory $validator */
        $validator = $this->app['validator'];

        $validator->extend('currency_iso',       CurrencyValidator::class.'@validateCurrencyIso');
        $validator->extend('currency_supported', CurrencyValidator::class.'@validateCurrencySupported');
    }
}
