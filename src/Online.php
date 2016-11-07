<?php

namespace Smalot\Online;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Smalot\Online\Ressources\StorageC14;

/**
 * Class Online
 * @package Smalot\Online
 */
class Online
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Online constructor.
     * @param string $token
     * @param ClientInterface $httpClient
     */
    public function __construct($token = null, ClientInterface $httpClient = null)
    {
        if (is_null($httpClient)) {
            $httpClient = new Client();
        }

        $this->token = $token;
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param mixed $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return StorageC14
     */
    public function storageC14()
    {
        return new StorageC14($this);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function call($url, $method = 'GET', $parameters = array())
    {
        $uri = 'https://api.online.net/api/v1/'.ltrim($url, '/');

        $options = array(
          'headers' => array(
            'Authorization' => 'Bearer '.$this->getToken(),
            'X-Pretty-JSON' => '1',
          ),
        );

        if ($method == 'GET') {
            if ($parameters) {
                $uri .= '?' . http_build_query($parameters);
            }
        } else {
            $options['json'] = $parameters;
        }

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->request($method, $uri, $options);
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }
}
