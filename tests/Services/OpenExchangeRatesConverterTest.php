<?php namespace Arcanedev\Currencies\Tests\Services;

/**
 * Class     OpenExchangeRatesConverterTest
 *
 * @package  Arcanedev\Currencies\Tests\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OpenExchangeRatesConverterTest extends AbstractApiServiceTestCase
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
        return 'openexchangerates';
    }

    /**
     * Get the service class.
     *
     * @return string
     */
    protected function getServiceClass()
    {
        return \Arcanedev\Currencies\Services\OpenExchangeRatesService::class;
    }

    /**
     * Get the service configs.
     *
     * @return array
     */
    protected function getServiceConfig()
    {
        return [
            'api-id' => 'YOUR_API_ID_HERE',
        ];
    }

    /**
     * Get API rates from the json file.
     *
     * @return array
     */
    protected function getApiRates()
    {
        $response = $this->getApiResponseFixture(true);

        return $response['rates'];
    }
}
