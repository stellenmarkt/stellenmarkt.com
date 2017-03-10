<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Service;

use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\StorageFactory;
use Zend\Http\Client;
use Zend\Json\Json;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressClient implements WordpressClientInterface
{
    /**
     *
     *
     * @var Client
     */
    private $httpClient;

    /**
     *
     *
     * @var StorageInterface
     */
    private $cache;

    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     *
     * @return self
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        if (!$this->cache) {
            $this->cache = StorageFactory::factory([
                'adapter' => [
                    'name' => 'memory',
                    'options' => [
                        'memory_limit' => '100%',
                    ],
                ],
            ]);
        }

        return $this->cache;
    }

    /**
     * @param \Zend\Http\Client $httpClient
     *
     * @return self
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @return \Zend\Http\Client
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }



    public function getPages()
    {
        return $this->request('/pages');
    }

    public function getPage($id)
    {
        return $this->request('/pages/' . $id);
    }

    private function request($path)
    {
        $cache  = $this->getCache();
        $hit    = false;
        $result = $cache->getItem($path, $hit);

        if ($hit) {
            return $result;
        }

        $client = $this
            ->getHttpClient()
            ->resetParameters(/*clearCookies*/ false, /*clearAuth*/ false)
            ->setUri($this->baseUrl . $path)
        ;

        $response = $client->send();
        $result   = $response->getBody();

        try {
            $result = Json::decode($result);
        } catch (\Zend\Json\Exception\RuntimeException $ex) {
            return (object) ['error' => true, 'code' => 'invalid_json_response', 'message' => 'Response has invalid json format.', 'data' => ['response' => $result]];
        }

        if (isset($result->code)) {
            $result->error = true;
        } else {
            $cache->setItem($path, $result);
        }

        return $result;
    }
}