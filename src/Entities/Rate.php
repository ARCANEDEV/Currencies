<?php namespace Arcanedev\Currencies\Entities;

use Arcanedev\Currencies\Contracts\Entities\Rate as RateContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class     Rate
 *
 * @package  Arcanedev\Currencies\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Rate implements RateContract, Arrayable, Jsonable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var double
     */
    protected $ratio;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Rate constructor.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  double  $ratio
     */
    public function __construct($from, $to, $ratio)
    {
        $this->from  = $from;
        $this->to    = $to;
        $this->ratio = $ratio;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the `from` currency iso.
     *
     * @return string
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * Get the `from` currency.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getFromCurrency()
    {
        return currency_manager()->findOrFail($this->from());
    }

    /**
     * Get the `to` currency iso.
     *
     * @return string
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * Get the `to` currency.
     *
     * @return \Arcanedev\Currencies\Entities\Currency
     */
    public function getToCurrency()
    {
        return currency_manager()->findOrFail($this->to());
    }

    /**
     * Get the rate ratio.
     *
     * @return float
     */
    public function ratio()
    {
        return $this->ratio;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a rate instance.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  double  $ratio
     *
     * @return self
     */
    public static function make($from, $to, $ratio)
    {
        return new self($from, $to, $ratio);
    }

    /**
     * Convert the amount.
     *
     * @param  double|int  $amount
     *
     * @return double|int
     */
    public function convert($amount)
    {
        return $amount * $this->ratio();
    }

    /**
     * Reverse the rate.
     *
     * @return self
     */
    public function reverse()
    {
        return self::make(
            $this->to(),
            $this->from(),
            round(1 / $this->ratio(), 6)
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'from'  => $this->from(),
            'to'    => $this->to(),
            'ratio' => $this->ratio(),
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
