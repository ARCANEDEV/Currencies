<?php namespace Arcanedev\Currencies\Services;

use Arcanedev\Currencies\Exceptions\ApiException;
use Illuminate\Support\Arr;

/**
 * Class     CurrencyLayerService
 *
 * @package  Arcanedev\Currencies\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CurrencyLayerService extends AbstractService
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
     * @var  string
     */
    protected $accessKey;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array $configs
     */
    protected function setProviderConfigs(array $configs)
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
     * @param  string $from
     * @param  array $to
     *
     * @return \Arcanedev\Currencies\Entities\RateCollection
     */
    protected function request($from, array $to = [])
    {
        $to = empty($to) ? null : implode(',', $to);

        $response = $this->client->get('/live', [
            'access_key' => $this->getAccessKey(),
            'source'     => $from,
            'currencies' => $to,
        ]);

        $data  = json_decode($response, true);

        $rates = [];

        foreach ($data['quotes'] as $key => $rate) {
            $key         = preg_replace('/'.preg_quote($from, '/').'/', '', $key, 1);
            $rates[$key] = $rate;
        }

        return $rates;
    }
}
