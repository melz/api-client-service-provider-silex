<?php
namespace Triadev\ApiClientService\Manager;

use Triadev\ApiClientService\Client\AbstractApiClient;

/**
 * Class ApiClientManager
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\ApiClientService\Manager
 */
class ApiClientManager
{
    /**
     * @var array
     */
    private $client = [];

    /**
     * ApiClientServiceManager constructor.
     */
    public function __construct(){}

    /**
     * Set api client
     *
     * @param string $key
     * @param AbstractApiClient $client
     */
    public function setClient($key, AbstractApiClient $client){
        $this->client[$key] = $client;
    }

    /**
     * Get api client
     * 
     * @param string $key
     * @return AbstractApiClient
     */
    public function getClient($key){
        return isset($key, $this->client) ? $this->client[$key] : null;
    }

    /**
     * Get api client keys
     *
     * @return array
     */
    public function getClientKeys(){
        return array_keys($this->client);
    }
}