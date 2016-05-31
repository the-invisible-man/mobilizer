<?php

namespace App\Lib\Packages\Geo\Services\Google;

use App\Lib\Packages\Core\Exceptions\ConfigNotFoundException;
use App\Lib\Packages\Core\Validators\ConfigValidatorTrait;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\Response;
use App\Lib\Packages\Geo\Services\Google\Exceptions\GoogleMapsRequestNotOkatException;

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

    // Response statuses
    const   OK                  = "OK",
            INVALID_REQUEST     = "INVALID_REQUEST",
            OVER_QUERY_LIMIT    = "OVER_QUERY_LIMIT",
            REQUEST_DENIED      = "REQUEST_DENIED",
            UNKNOWN_ERROR       = "UNKNOWN_ERROR",
            ZERO_RESULTS        = "ZERO_RESULTS";

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
        $options        = null;

        if ($method == self::GET) {
            $options    = ['query' => $data];
            $data       = null;
        }

        $request        = $this->httpClient->createRequest($method, $url, null, $data, $options);

        try {
            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            throw $e;
        }
        return $this->handleResponse($response);
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
     * @param string $status
     * @throws GoogleMapsRequestNotOkatException
     */
    public function handleResponseStatus(string $status) {
        switch($status) {
            case self::OK:
                return;
            case self::INVALID_REQUEST:
            case self::OVER_QUERY_LIMIT:
            case self::REQUEST_DENIED:
            case self::UNKNOWN_ERROR:
            case self::ZERO_RESULTS:
                break;
        }
    }

    /**
     * @param Response $requestInterface
     * @return array
     */
    private function handleJson(Response $requestInterface) : array
    {
        $response = json_decode($requestInterface->getBody(), true);
        $this->handleResponseStatus($response['status']);
        return $response;
    }

    /**
     * @param Response $requestInterface
     * @return array
     */
    private function handleXml(Response $requestInterface) : array
    {
        // TODO: implement this bullshit
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