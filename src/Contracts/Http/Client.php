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
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a get request.
     *
     * @param  string  $url
     *
     * @return string
     */
    public function get($url);
}
