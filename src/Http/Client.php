<?php namespace Arcanedev\Currencies\Http;

use Arcanedev\Currencies\Contracts\Http\Client as ClientInterface;

/**
 * Class     Client
 *
 * @package  Arcanedev\Currencies\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Client implements ClientInterface
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
    public function get($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
