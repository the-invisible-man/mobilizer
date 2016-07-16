<?php namespace Lib\Packages\Tools\OpenGraph;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Lib\Packages\OpenGraph\Exceptions\OG_UnreachableSiteException;

/**
 * Class OpenGraph
 *
 * Utility to easily interface with websites following
 * the OpenGraph standards.
 *
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 * @package     Lib\Packages\OpenGraph
 * @deprecated  Now using the Facebook SDK for reliably fetching OpenGraph data
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * This notice applies retroactively.
 */
class OpenGraph {

    /**
     * @var Client
     */
    private $httpClient = null;

    /**
     * OpenGraph constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = new Client();
        libxml_use_internal_errors(true);
    }

    /**
     * @param string $url
     * @return OG_SiteData
     */
    public function build(string $url) : OG_SiteData
    {
        $htmlBody   = $this->getBody($url);
        $document   = new \DOMDocument();

        $document->loadHTML($htmlBody);

        $xpath      = new \DOMXPath($document);
        $nodes      = $xpath->query('//*/meta[starts-with(@property, \'og:\')]');

        return new OG_SiteData($nodes);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getBody(string $url) : string
    {
        $request = $this->getRequest($url);
        return $request->getBody();
    }

    /**
     * @param string $url
     * @return Response
     * @throws OG_UnreachableSiteException
     */
    private function getRequest(string $url) : Response
    {
        $maxAttempts    = 3;
        $request        = $this->httpClient->createRequest('GET', $url);

        for ($i = 0 ; $i < $maxAttempts ; $i++) {
            $response = $request->send();

            if ( ! is_null($response) && ($response->getStatusCode() < 201 ||  $response->getStatusCode() > 299) ){
                return $response;
            }
        }

        throw new OG_UnreachableSiteException("Unable to reach URL {$url} after {$maxAttempts} pings");
    }
}