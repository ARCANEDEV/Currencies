<?php namespace Arcanedev\Currencies\Entities;

use Arcanedev\Currencies\Contracts\Entities\Currency as CurrencyContract;
use Arcanedev\Currencies\Exceptions\CurrencyNotFoundException;
use Arcanedev\Support\Collection;

/**
 * Class     CurrencyCollection
 *
 * @package  Arcanedev\Currencies\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get a currency from the collection by iso code.
     *
     * @param  string      $iso
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     */
    public function get($iso, $default = null)
    {
        return parent::get($iso, $default);
    }

    /**
     * Load the currencies.
     *
     * @param  array  $currencies
     * @param  bool   $includeNonIso
     *
     * @return self
     */
    public function load(array $currencies, $includeNonIso = false)
    {
        $this->reset();

        foreach ($currencies as $group => $items) {
            if ($group !== 'iso' && ! $includeNonIso) continue;

            $this->loadMany($items, ($group === 'iso'));
        }

        return $this;
    }

    /**
     * Add a currency to the collection.
     *
     * @param  string  $key
     * @param  array   $attributes
     *
     * @return self
     */
    public function add($key, array $attributes)
    {
        $key        = strtoupper($key);
        $attributes = compact('key') + $attributes;

        return $this->addOne(Currency::make($key, $attributes));
    }

    /**
     * Add a Currency object to the collection.
     *
     * @param  \Arcanedev\Currencies\Contracts\Entities\Currency  $currency
     *
     * @return self
     */
    public function addOne(CurrencyContract $currency)
    {
        $this->put($currency->key, $currency);

        return $this;
    }

    /**
     * Check if the currency exists.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function findOrFails($iso)
    {
        $iso = strtoupper($iso);

        if ( ! $this->has($iso)) {
            throw new CurrencyNotFoundException("The Currency with the ISO code [$iso] not found");
        }

        return $this->get($iso);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add many currencies.
     *
     * @param  array  $items
     * @param  bool   $isIso
     */
    protected function loadMany($items, $isIso = false)
    {
        foreach ($items as $key => $attributes) {
            $attributes = ['is_iso' => $isIso] + $attributes;

            $this->add($key, $attributes);
        }
    }
}
