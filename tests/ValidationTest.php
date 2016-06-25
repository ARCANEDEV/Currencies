<?php namespace Arcanedev\Currencies\Tests;

/**
 * Class     ValidationTest
 *
 * @package  Arcanedev\Currencies\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValidationTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_validate_currency_iso()
    {
        $currencies = currency_manager()->currencies();

        foreach ($currencies as $iso => $currency) {
            $validator = $this->makeValidator([
                'currency' => $iso
            ],[
                'currency' => 'required|currency_iso'
            ]);

            $this->assertTrue($validator->passes());
            $this->assertFalse($validator->fails());
            $this->assertSame(0, $validator->messages()->count());
        }

        $validator = $this->makeValidator([
            'currency' => 'ZZZ'
        ],[
            'currency' => 'required|currency_iso'
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertSame(1, $validator->messages()->count());
        $this->assertSame(
            'The currency field must be a valid ISO currency code.',
            $validator->messages()->first('currency')
        );
    }

    /** @test */
    public function it_can_validate_supported_currency()
    {
        $currencies = currency_manager()->currencies();
        $supported  = currency_manager()->getSupported();

        foreach ($currencies as $iso => $currency) {
            $validator = $this->makeValidator([
                'currency' => $iso
            ],[
                'currency' => 'required|currency_supported'
            ]);

            if (in_array($iso, $supported)) {
                $this->assertTrue($validator->passes());
                $this->assertFalse($validator->fails());
                $this->assertSame(0, $validator->messages()->count());
            }
            else {
                $this->assertFalse($validator->passes());
                $this->assertTrue($validator->fails());
                $this->assertSame(1, $validator->messages()->count());
                $this->assertSame(
                    'The selected currency is invalid.',
                    $validator->messages()->first('currency')
                );
            }
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a validator.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     *
     * @return \Illuminate\Validation\Validator
     */
    public function makeValidator(array $data, array $rules, array $messages = [])
    {
        /** @var \Illuminate\Validation\Factory $validator */
        $validator = $this->app['validator'];

        return $validator->make($data, $rules, $messages);
    }
}
