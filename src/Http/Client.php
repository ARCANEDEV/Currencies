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
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Base URL.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * CURL object.
     *
     * @var resource
     */
    protected $curl;

    /**
     * The response result.
     *
     * @var mixed
     */
    protected $response;

    /**
     * The error code.
     *
     * @var int
     */
    protected $errorCode;

    /**
     * The error message.
     *
     * @var string
     */
    protected $errorMessage;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor & Destructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Destroy the instance.
     */
    public function __destruct()
    {
        $this->close();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get base URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return rtrim($this->baseUrl, '/');
    }

    /**
     * Set base URL.
     *
     * @param  string $baseUrl
     *
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Set array options.
     *
     * @param  array  $options
     *
     * @return self
     */
    public function setOptionArray(array $options)
    {
        curl_setopt_array($this->curl, $options);

        return $this;
    }

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
    public function get($uri, array $params = [])
    {
        $this->init();

        $this->setOptionArray([
            CURLOPT_URL            => $this->prepareUrl($uri, $params),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);

        $this->execute();
        $this->close();

        return $this->response;
    }

    /**
     * Init curl.
     */
    private function init()
    {
        $this->curl = curl_init();
    }

    /**
     * Execute curl request.
     */
    private function execute()
    {
        $this->response     = curl_exec($this->curl);
        $this->errorCode    = curl_errno($this->curl);
        $this->errorMessage = curl_error($this->curl);
    }

    /**
     * Close the CURL object.
     */
    private function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare the URL.
     *
     * @param  string  $uri
     * @param  array   $params
     *
     * @return string
     */
    private function prepareUrl($uri, array $params)
    {
        $query = empty($params) ? '' : '?' . http_build_query(array_filter($params));

        return $this->getBaseUrl() . '/' . ltrim($uri, '/') . $query;
    }
}
