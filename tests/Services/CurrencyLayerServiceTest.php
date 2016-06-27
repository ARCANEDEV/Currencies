<?php namespace Arcanedev\Currencies\Tests\Services;

/**
 * Class     CurrencyLayerServiceTest
 *
 * @package  Arcanedev\Currencies\Tests\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyLayerServiceTest extends AbstractApiServiceTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Common Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the service key.
     *
     * @return string
     */
    protected function getServiceKey()
    {
        return 'currencylayer';
    }

    /**
     * Get the service class.
     *
     * @return string
     */
    protected function getServiceClass()
    {
        return \Arcanedev\Currencies\Services\CurrencyLayerService::class;
    }

    /**
     * Get the service configs.
     *
     * @return array
     */
    protected function getServiceConfig()
    {
        return [
            'access-key' => 'YOUR_ACCESS_KEY_HERE',
        ];
    }

    /**
     * Get API rates from the json file.
     *
     * @return array
     */
    protected function getApiRates()
    {
        $rates    = [];
        $response = $this->getApiResponseFixture(true);

        foreach ($response['quotes'] as $key => $ratio) {
            $rates[substr($key, 3)] = $ratio;
        }

        return $rates;
    }
}
