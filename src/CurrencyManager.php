<?php namespace Arcanedev\Currencies;

use \Arcanedev\Currencies\Contracts\CurrencyManager as CurrencyManagerContract;
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
     * Get the default currency entity.
     *
     * @return \Arcanedev\Currencies\Contracts\Entities\Currency
     */
    public function getDefaultCurrency()
    {
        return $this->currencies->get($this->default);
    }

    /**
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
     * Get a currency by ISO.
     *
     * @param  string $iso
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function get($iso)
    {
        return $this->currencies()->get($iso);
    }

    /**
     * Get all currencies.
     *
     * @return array
     */
    public function allCurrencies()
    {
        return $this->currencies()->all();
    }

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
     * Format the amount.
     *
     * @param  string  $iso
     * @param  int     $amount
     * @param  int     $decimals
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\CurrencyNotFoundException
     */
    public function format($iso, $amount, $decimals = 2)
    {
        return $this->currencies()
            ->findOrFails($iso)
            ->format($amount, $decimals);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if non ISO Currencies included.
     *
     * @return bool
     */
    public function isNonIsoIncluded()
    {
        return $this->nonIsoIncluded;
    }
}
