<?php
namespace Triadev\ApiClientService\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class AbstractApiClient
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\ApiClientService\Client
 */
abstract class AbstractApiClient
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @var Client
     */
    private $guzzle_client;

    /**
     * @var string
     */
    private $base_url;

    /**
     * AbstractApiClient constructor.
     *
     * @param string $scheme
     * @param string $url
     * @param int $port
     */
    private function __construct($scheme, $url, $port){
        $this->guzzle_client = new Client();
        $this->base_url = $this->_generateBaseUrl($scheme, $url, $port);
    }

    /**
     * Create api client
     *
     * @param string $scheme
     * @param string $url
     * @param int $port
     * @return AbstractApiClient
     */
    public static function create($scheme, $url, $port){
        $class = get_called_class();
        return new $class($scheme, $url, (int)$port);
    }

    /**
     * Generate base url
     *
     * @param string $scheme
     * @param string $url
     * @param int $port
     * @return string
     */
    private function _generateBaseUrl($scheme, $url, $port){
        $base_url = '';

        /**
         * Schema
         */
        if(in_array($scheme, ['http', 'https'])){
            $base_url .= $scheme . '://';
        }

        /**
         * Url
         */
        $base_url .= $url;

        /**
         * Port
         */
        if($port != 80){
            $base_url .= ':' . $port;
        }

        return $base_url;
    }

    /**
     * Generate endpoint
     *
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    private function _generateEndpoint($endpoint, array $params = []){
        foreach ($params as $param_key => $param_value){
            $endpoint = str_replace(":{$param_key}", $param_value, $endpoint);
        }
        return $endpoint;
    }

    /**
     * Request
     *
     * @param string $http_method
     * @param string $endpoint
     * @param \stdClass $data
     * @param array $params
     * @param array $headers
     * @return string
     * @throws \Exception
     */
    public function request($http_method, $endpoint, \stdClass $data = null, array $params = [], array $headers = []){
        if(!in_array($http_method, ['GET', 'POST', 'PUT', 'DELETE'])){
            throw new \Exception("No valid http method!");
        }

        /**
         * Data
         */
        if($data){
            $data = json_encode((array)$data);
        }

        /**
         * Endpoint
         */
        $endpoint = $this->_generateEndpoint($endpoint, $params);

        /** @var Request $request */
        $request = new Request($http_method, $this->base_url . '/' . $endpoint, $headers, $data);

        /** @var Response $response */
        $response = $this->guzzle_client->send($request);

        return json_decode($response->getBody()->getContents());
    }
}