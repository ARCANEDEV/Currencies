<?php namespace Arcanedev\Currencies\Services;

use Arcanedev\Currencies\Exceptions\ApiException;
use Illuminate\Support\Arr;

/**
 * Class     OpenExchangeRatesService
 *
 * @package  Arcanedev\Currencies\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OpenExchangeRatesService extends AbstractService
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The base URL.
     *
     * @var string
     */
    protected $baseUrl = 'http://openexchangerates.org/api';

    /**
     * The API ID.
     *
     * @var string
     */
    protected $apiId;

    /**
     * The Pro Plan.
     *
     * @var bool
     */
    protected $proPlan = false;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array  $configs
     */
    protected function setProviderConfigs(array $configs)
    {
        $this->apiId   = Arr::get($configs, 'api-id');
        $this->proPlan = Arr::get($configs, 'pro-plan', false);
    }

    /**
     * Get the API ID.
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\ApiException
     */
    private function getAppId()
    {
        if ( ! $this->apiId) {
            throw new ApiException('OpenExchangeRates.org requires an app key.');
        }

        return $this->apiId;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make an API request.
     *
     * @param  string  $from
     * @param  array   $to
     *
     * @return array
     *
     * @throws \Arcanedev\Currencies\Exceptions\ApiException
     */
    protected function request($from, array $to)
    {
        $response = $this->client->get('/latest.json', [
            'app_id'  => $this->getAppId(),
            'base'    => $from,
            'symbols' => $this->proPlan ? implode(',', $to) : null,
        ]);

        $data = json_decode($response, true);

        return $data['rates'];
    }
}
