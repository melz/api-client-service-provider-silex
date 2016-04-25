<?php
namespace Triadev\ApiClientService;

use Triadev\ApiClientService\Manager\ApiClientManager;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class SilexApiClientServiceProvider
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\ApiClientService
 */
class SilexApiClientServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $api_client_manager = new ApiClientManager();

        foreach ($this->params['clients'] as $key => $client_config){
            $client_namespace = $this->params['namespace'] . ucfirst($key) . 'ApiClient';
            if(class_exists($client_namespace)){
                $api_client = $client_namespace::create(
                    isset($client_config['scheme']) ? $client_config['scheme'] : 'http',
                    $client_config['url'],
                    isset($client_config['port']) ? $client_config['port'] : 80
                );

                $api_client_manager->setClient($key, $api_client);
            }
        }

        $app['api_client_manager'] = $api_client_manager;
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}