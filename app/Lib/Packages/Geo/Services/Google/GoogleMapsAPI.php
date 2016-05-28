<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Core\Exceptions\ConfigNotFoundException;
use App\Lib\Packages\Core\Validators\ConfigValidatorTrait;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

/**
 * Class GoogleMapsRequest
 *
 * Parent of any google maps API implementation.
 * Google treats directions and geocoding as two separate
 * APIs.
 *
 * @package App\Lib\Packages\Geo\Services\Google
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
abstract class GoogleMapsAPI {

    use ConfigValidatorTrait;

    const   POST    = 'POST',
            GET     = 'GET',
            DELETE  = 'DELETE',
            PUT     = 'PUT',
            JSON    = 'application/json',
            XML     = 'application/xml';

    /**
     * @var array
     */
    private $config;

    /**
     * Child class can add its required configuration
     * in this array.
     * @var array
     */
    protected $requiredConfig = [];

    /**
     * This is configuration that is always assumed
     * to exist in the child class.
     * @var array
     */
    private $baseRequired = ['url', 'apiKey', 'responseType'];

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * GoogleMapsRequest constructor.
     * @param array $config
     * @param Client $httpClient
     */
    public function __construct(array $config, Client $httpClient)
    {
        array_merge($this->requiredConfig, $this->baseRequired);

        $this->config       = $this->validateConfig($config);
        $this->httpClient   = $httpClient;
    }

    /**
     * @param array $data
     * @param string $endpoint
     * @param string $method
     * @return array
     */
    public function do(array $data, string $endpoint = '', string $method = self::GET) : array
    {
        $url            = $this->getConfig('url') . $endpoint;
        $data['key']    = $this->getConfig('apiKey');
        $request        = $this->httpClient->createRequest($method, $url, $data);

        return $this->handleResponse($request->send());
    }

    /**
     * @param Response $requestInterface
     * @return array
     * @throws ConfigNotFoundException
     */
    private function handleResponse(Response $requestInterface) : array
    {
        switch($this->getConfig('responseType')) {
            case self::JSON:
                return $this->handleJson($requestInterface);
            case self::XML:
                return $this->handleXml($requestInterface);
            default:
                return $requestInterface;
        }
    }

    /**
     * @param Response $requestInterface
     * @return array
     */
    private function handleJson(Response $requestInterface) : array
    {
        return json_decode($requestInterface->getBody(), true);
    }

    /**
     * @param Response $requestInterface
     * @return array
     */
    private function handleXml(Response $requestInterface) : array
    {
        // TODO: implement this shit
        return [];
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ConfigNotFoundException
     */
    protected function getConfig(string $key)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new ConfigNotFoundException("Tried to access invalid configuration \"{$key}\"");
        }

        return $this->config[$key];
    }
}