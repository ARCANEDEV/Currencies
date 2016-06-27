<?php

use Arcanedev\Currencies\Contracts\ConverterManager;
use Arcanedev\Currencies\Contracts\CurrencyManager;

if ( ! function_exists('currency_manager')) {
    /**
     * Get the Currency Manager instance.
     *
     * @return \Arcanedev\Currencies\Contracts\CurrencyManager
     */
    function currency_manager() {
        return app(CurrencyManager::class);
    }
}

if ( ! function_exists('currency_converter')) {
    /**
     * Get the Currency Converter instance (service).
     *
     * @param  string|null  $driver
     *
     * @return \Arcanedev\Currencies\Contracts\Services\CurrencyService
     */
    function currency_converter($driver = null) {
        return app(ConverterManager::class)->driver($driver);
    }
}
