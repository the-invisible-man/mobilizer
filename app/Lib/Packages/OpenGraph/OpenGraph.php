<?php

namespace Lib\Packages\OpenGraph;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Lib\Packages\OpenGraph\Exceptions\OG_UnreachableSiteException;

class OpenGraph {

    /**
     * @var Client
     */
    private $guzzleClient = null;

    public function __construct()
    {
        libxml_use_internal_errors(true);
    }

    /**
     * @param string $url
     * @return OG_SiteData
     */
    public function get(string $url) : OG_SiteData {
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
    private function getBody(string $url) : string {
        $request = $this->getRequest($url);
        return $request->getBody();
    }

    /**
     * @param string $url
     * @return Response
     * @throws OG_UnreachableSiteException
     */
    private function getRequest(string $url) : Response {
        if ( $this->guzzleClient === null ){
            $this->guzzleClient = new Client();
        }

        $maxAttempts    = 3;

        for( $i = 0 ; $i < $maxAttempts ; $i++) {
            $request = $this->guzzleClient->get($url)->getResponse();

            if ( $request->getStatusCode() === 200 ){
                return $request;
            }
        }

        throw new OG_UnreachableSiteException("Unable to reach URL {$url} after {$maxAttempts} pings");
    }
}