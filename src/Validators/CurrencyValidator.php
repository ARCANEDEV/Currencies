<?php namespace Arcanedev\Currencies\Validators;

use Arcanedev\Currencies\Contracts\CurrencyManager;

/**
 * Class     CurrencyValidator
 *
 * @package  Arcanedev\Currencies\Validators
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyValidator
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Contracts\CurrencyManager */
    protected $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CurrencyValidator constructor.
     *
     * @param  \Arcanedev\Currencies\Contracts\CurrencyManager  $manager
     */
    public function __construct(CurrencyManager $manager)
    {
        $this->manager = $manager;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function validateCurrencyIso($attribute, $value, $parameters, $validator)
    {
        $manager = $this->manager;

        /** @var \Illuminate\Validation\Validator $validator */
        $validator->addReplacer('currency_iso', function($message, $attribute, $rule, $parameters) use ($manager) {
            return trans("currencies::validation.currency.iso", ['attribute' => $attribute]);
        });

        return in_array($value, $manager->currencies()->keys()->toArray());
    }

    /**
     * @param  string                            $attribute
     * @param  mixed                             $value
     * @param  array                             $parameters
     * @param  \Illuminate\Validation\Validator  $validator
     *
     * @return bool
     */
    public function validateCurrencySupported($attribute, $value, $parameters, $validator)
    {
        $manager = $this->manager;

        /** @var \Illuminate\Validation\Validator $validator */
        $validator->addReplacer('currency_supported', function($message, $attribute, $rule, $parameters) use ($manager) {
            return trans("currencies::validation.currency.supported", ['attribute' => $attribute]);
        });

        return in_array($value, $manager->getSupported());
    }
}
