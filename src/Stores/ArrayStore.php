<?php namespace Arcanedev\Currencies\Stores;

use Arcanedev\Currencies\Bases\AbstractService;
use Illuminate\Support\Arr;

/**
 * Class     ArrayStore
 *
 * @package  Arcanedev\Currencies\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ArrayStore extends AbstractService
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    protected $configs = [];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array $configs
     */
    protected function setConfigs(array $configs)
    {
        $this->configs = $configs;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get currencies rates.
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    public function rates()
    {
        return $this->makeRatesCollection(
            $this->getDefault(),
            Arr::get($this->configs, 'rates', [])
        );
    }
}
