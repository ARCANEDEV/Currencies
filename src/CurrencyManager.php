<?php namespace Arcanedev\Currencies;

use Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
use Arcanedev\Currencies\Entities\CurrencyCollection;
use Illuminate\Support\Arr;

/**
 * Class     CurrencyManager
 *
 * @package  Arcanedev\Currencies
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyManager implements CurrencyManagerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Default currency.
     *
     * @var string
     */
    protected $default;

    /**
     * Supported currencies.
     *
     * @var array
     */
    protected $supported      = [];

    /**
     * Non ISO Currencies included.
     *
     * @var bool
     */
    protected $nonIsoIncluded = false;

    /**
     * The currencies collection.
     *
     * @var  \Arcanedev\Currencies\Entities\CurrencyCollection
     */
    protected $currencies;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CurrencyManager constructor.
     *
     * @param  array  $configs
     */
    public function __construct(array $configs)
    {
        $this->default        = Arr::get($configs, 'default', 'USD');
        $this->supported      = Arr::get($configs, 'supported', ['USD']);
        $this->nonIsoIncluded = Arr::get($configs, 'include-non-iso', false);
        $this->currencies     = new CurrencyCollection;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the default currency iso code.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Get the default currency entity.
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     */
    public function getDefaultCurrency()
    {
        return $this->currencies->get($this->getDefault());
    }

    /**
     * Get supported currencies (iso codes).
     *
     * @return array
     */
    public function getSupported()
    {
        return $this->supported;
    }

    /**
     * Check if non ISO Currencies included.
     *
     * @return bool
     */
    public function isNonIsoIncluded()
    {
        return $this->nonIsoIncluded;
    }

    /**
     * Get the currencies collection.
     *
     * @return \Arcanedev\Currencies\Entities\CurrencyCollection
     */
    public function currencies()
    {
        return $this->currencies;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load the currencies.
     *
     * @param  array  $currencies
     *
     * @return self
     */
    public function load(array $currencies)
    {
        $this->currencies()->load($currencies, $this->nonIsoIncluded);

        return $this;
    }

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
        return $this->currencies()->get($iso, $default);
    }

    /**
     * Get a currency or fail if not exists.
     *
     * @param  string  $iso
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function findOrFail($iso)
    {
        return $this->currencies()->findOrFails($iso);
    }

    /**
     * Get the supported currencies collection.
     *
     * @return \Arcanedev\Currencies\Entities\CurrencyCollection
     */
    public function getSupportedCurrencies()
    {
        $currencies = $this->currencies();
        $supported  = array_map(function ($iso) use ($currencies) {
            return $currencies->findOrFails($iso);
        }, array_combine($this->getSupported(), $this->getSupported()));

        return CurrencyCollection::make($supported);
    }

    /**
     * Format the amount.
     *
     * @param  string      $iso
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function format($iso, $amount, $decimals = 2)
    {
        return $this->findOrFail($iso)->format($amount, $decimals);
    }

    /**
     * Format the amount by the default iso.
     *
     * @param  double|int  $amount
     * @param  int         $decimals
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function formatDefault($amount, $decimals = 2)
    {
        return $this->format($this->getDefault(), $amount, $decimals);
    }

    /**
     * Get the currency symbol by iso code.
     *
     * @param  string  $iso
     *
     * @return string
     */
    public function symbol($iso)
    {
        return $this->findOrFail($iso)->symbol;
    }
}
