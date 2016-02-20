<?php

namespace Lib\Packages\OpenGraph;

/**
 * Class OG_SiteData
 *
 * Container for XPath extracted OpenGraph meta data.
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 * @package Lib\Packages\OpenGraph
 */
class OG_SiteData {

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var \DOMNodeList
     */
    private $nodes;

    const   TITLE           = 'og:title',
            URL             = 'og:url',
            IMAGE           = 'og:image',
            TYPE            = 'og:type',
            DESCRIPTION     = 'og:description',
            SITE            = 'og:site_name';

    /**
     * OG_SiteData constructor.
     * @param \DOMNodeList $nodes
     */
    public function __construct(\DOMNodeList $nodes)
    {
        $this->nodes = $nodes;
        $this->extract($nodes);
    }

    /**
     * @param \DOMNodeList $nodes
     */
    private function extract(\DOMNodeList $nodes) {
        foreach($nodes as $node){
            /**
             * @var \DOMElement $node
             */
            $this->data[$node->getAttribute("property")] = $node->getAttribute("content");
        }
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->fetchNode(self::TITLE);
    }

    /**
     * @return string
     */
    public function getUrl() : string {
        return $this->fetchNode(self::URL);
    }

    /**
     * @return string
     */
    public function getImage() : string {
        return $this->fetchNode(self::IMAGE);
    }

    /**
     * @return string
     */
    public function getDescription() : string {
        return $this->fetchNode(self::DESCRIPTION);
    }

    /**
     * @return string
     */
    public function getSite() : string {
        return $this->fetchNode(self::SITE);
    }

    /**
     * @param string $property
     * @return string
     */
    public function fetchNode(string $property) : string{
        return isset($this->data[$property]) ? $this->data[$property] : null;
    }
}