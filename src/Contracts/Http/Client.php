<?php namespace Arcanedev\Currencies\Contracts\Http;

/**
 * Interface  Client
 *
 * @package   Arcanedev\Currencies\Contracts\Http
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Client
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get base URL.
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Set base URL.
     *
     * @param  string  $baseUrl
     *
     * @return self
     */
    public function setBaseUrl($baseUrl);

    /**
     * Set array options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptionArray(array $options);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a get request.
     *
     * @param  string  $uri
     * @param  array   $params
     *
     * @return string
     */
    public function get($uri, array $params = []);
}
