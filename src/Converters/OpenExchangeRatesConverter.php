<?php namespace Arcanedev\Currencies\Converters;

use Arcanedev\Currencies\Entities\RateCollection;
use Illuminate\Support\Arr;

/**
 * Class     OpenExchangeRatesConverter
 *
 * @package  Arcanedev\Currencies\Converters
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class OpenExchangeRatesConverter extends AbstractConverter
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
     * @var bool
     */
    protected $proPlan;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the configs.
     *
     * @param  array $configs
     *
     * @return mixed
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
     * @throws \Exception
     */
    private function getAppId()
    {
        if ( ! $this->apiId) {
            throw new \Exception('OpenExchangeRates.org requires an app key.');
        }

        return $this->apiId;
    }

    private function getFromCurrency($from)
    {
        if (is_null($from)) {
            $from = $this->getDefaultCurrency();
        }

        return $from;
    }

    private function getToCurrencies($to)
    {
        if (is_null($to)) {
            return array_diff(
                $this->getSupportedCurrencies(),
                [$this->getDefaultCurrency()]
            );
        }

        return $to;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get currencies rates.
     *
     * @param  string|null        $from
     * @param  array|string|null  $to
     *
     * @return RateCollection
     */
    public function rates($from = null, $to = null)
    {
        $from = $this->getFromCurrency($from);
        $to   = $this->getToCurrencies($to);

        $self     = $this;
        $callback = function () use ($self, $from, $to) {
            return $self->request($from, $to);
        };

        return $this->isCacheEnabled()
            ? $this->cache->remember($this->cacheKey, $this->cacheDuration, $callback)
            : call_user_func($callback);
    }

    /**
     * Make an API request.
     *
     * @param  string  $from
     * @param  array   $to
     *
     * @return RateCollection
     *
     * @throws \Exception
     */
    protected function request($from, array $to)
    {
        $params = array_filter([
            'app_id'  => $this->getAppId(),
            'base'    => $from,
            // TODO: Add a config parameter to limit results to specific currencies (Pro Plan)
            'symbols' => $this->proPlan ? implode(',', $to) : null,
        ]);

        $response = $this->client->get($this->getBaseUrl() . "/latest.json?".http_build_query($params));
        $data     = json_decode($response, true);

        return $this->prepareRates($from, $data['rates']);
    }

    /**
     * Prepare rates collection.
     *
     * @param  string  $from
     * @param  array   $rates
     *
     * @return RateCollection
     */
    protected function prepareRates($from, array $rates)
    {
        return RateCollection::make()->load($from, $rates);
    }
}
