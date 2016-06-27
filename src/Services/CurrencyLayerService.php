<?php namespace Arcanedev\Currencies\Services;

use Arcanedev\Currencies\Exceptions\ApiException;
use Illuminate\Support\Arr;

/**
 * Class     CurrencyLayerService
 *
 * @package  Arcanedev\Currencies\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyLayerService extends AbstractApiService
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
    protected $baseUrl = 'http://apilayer.net/api';

    /**
     * The Access Key.
     *
     * @var string
     */
    protected $accessKey;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array  $configs
     */
    protected function setConfigs(array $configs)
    {
        $this->accessKey = Arr::get($configs, 'access-key');
    }

    /**
     * Get the Access Key.
     *
     * @return string
     *
     * @throws \Arcanedev\Currencies\Exceptions\ApiException
     */
    private function getAccessKey()
    {
        if ( ! $this->accessKey) {
            throw new ApiException('Currencylayer.com requires an access key.');
        }

        return $this->accessKey;
    }

    /**
     * Get cache key.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return parent::getCacheKey() . '.currencylayer';
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
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    protected function request($from, array $to = [])
    {
        $response = $this->client->get('/live', [
            'access_key' => $this->getAccessKey(),
            'source'     => $from,
            'currencies' => empty($to) ? null : implode(',', $to),
        ]);

        $rates = [];
        $data  = json_decode($response, true);

        foreach ($data['quotes'] as $key => $ratio) {
            $rates[substr($key, 3)] = $ratio;
        }

        return $rates;
    }
}
