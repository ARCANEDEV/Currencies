<?php namespace Arcanedev\Currencies\Entities;

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
     * Add many currencies.
     *
     * @param  array  $items
     * @param  bool   $isIso
     */
    protected function loadMany($items, $isIso = false)
    {
        foreach ($items as $key => $currency) {
            $key      = strtoupper($key);
            $currency = [
                'key'    => $key,
                'is_iso' => $isIso,
            ] + $currency;

            $this->add($key, $currency);
        }
    }

    public function add($key, array $attributes)
    {
        $this->put($key, new Currency($key, $attributes));

        return $this;
    }

    /**
     * Check if the currency exists.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function findOrFails($iso)
    {
        $iso = strtoupper($iso);

        if ( ! $this->has($iso)) {
            throw new CurrencyNotFoundException(
                "The Currency with ISO [$iso] not found"
            );
        }

        return $this->get($iso);
    }
}
