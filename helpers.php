<?php

if ( ! function_exists('currency_manager')) {
    /**
     * Get the Currency Manager instance.
     *
     * @return \Arcanedev\Currencies\Contracts\CurrencyManager
     */
    function currency_manager () {
        return app(\Arcanedev\Currencies\Contracts\CurrencyManager::class);
    }
}
