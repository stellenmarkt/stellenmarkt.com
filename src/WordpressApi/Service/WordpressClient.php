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

    public function getPosts(array $args = [])
    {
        return $this->request('/posts', $args);
    }

    public function getPost($id, array $args = [])
    {
        return $this->request('/posts/' . $id, $args);
    }

    public function getPostBySlug($slug, array $args = [])
    {
        return $this->getArticleBySlug('post', $slug, $args);
    }

    public function getPages(array $args = [])
    {
        return $this->request('/pages', $args);
    }

    public function getPage($id, array $args = [])
    {
        return $this->request('/pages/' . $id, $args);
    }

    public function getPageBySlug($slug, array $args = [])
    {
        return $this->getArticleBySlug('page', $slug, $args);
    }

    private function getArticleBySlug($type, $slug, array $args = [])
    {
        $args   = array_merge($args, ['slug' => $slug]);

        $articles = $this->request('/' . $type . 's', $args);

        if (count($articles)) {
            return $articles[0];
        }

        return $this->error('slug_not_found', sprintf('No %s with the slug "%s" was found', $type, $slug));
    }

    public function request($path, array $args = [])
    {
        $cache    = $this->getCache();
        $cacheKey = $path;
        if ($args) { $cacheKey .= md5(serialize($args)); }
        $hit    = false;
        $result = $cache->getItem($cacheKey, $hit);

        if ($hit) {
            return $result;
        }

        $client = $this
            ->getHttpClient()
            ->resetParameters(/*clearCookies*/ false, /*clearAuth*/ false)
            ->setParameterGet($args)
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
            $cache->setItem($cacheKey, $result);
        }

        return $result;
    }

    private function error($code, $message)
    {
        return (object) [ 'error' => true, 'code' => $code, 'message' => $message ];
    }
}