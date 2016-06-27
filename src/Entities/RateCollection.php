<?php namespace Arcanedev\Currencies\Entities;

use Arcanedev\Currencies\Contracts\Entities\Rate as RateContract;
use Arcanedev\Support\Collection;

/**
 * Class     RateCollection
 *
 * @package  Arcanedev\Currencies\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RateCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The `from` currency iso.
     *
     * @var string
     */
    protected $from;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the `from` currency iso.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the `from` currency iso.
     *
     * @param  string  $from
     *
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load rates.
     *
     * @param  string  $from
     * @param  array   $rates
     *
     * @return self
     */
    public function load($from, array $rates)
    {
        $this->reset();
        $this->setFrom($from);

        foreach ($rates as $to => $ratio) {
            $this->add($to, $ratio);
        }

        return $this;
    }

    /**
     * Add a rate to the collection.
     *
     * @param  string      $to
     * @param  double|int  $ratio
     *
     * @return self
     */
    public function add($to, $ratio)
    {
        return $this->addRate(
            Rate::make($this->getFrom(), $to, $ratio)
        );
    }

    /**
     * Add a Rate object to the collection.
     *
     * @param  \Arcanedev\Currencies\Contracts\Entities\Rate  $rate
     *
     * @return self
     */
    public function addRate(RateContract $rate)
    {
        $this->put($rate->to(), $rate);

        return $this;
    }

    /**
     * Get a rate by `to` iso.
     *
     * @param  string  $key
     * @param  null    $default
     *
     * @return \Arcanedev\Currencies\Entities\Rate
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }
}
