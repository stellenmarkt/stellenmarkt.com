<?php
/**
 * YAWIK Gastro24 Module
 *
 * @filesource
 * @license MIT
 * @copyright  2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Listener;

use Gastro24\WordpressApi\Service\WordpressClient;
use Zend\EventManager\EventInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressContentSnippet 
{
    /**
     *
     *
     * @var \Gastro24\WordpressApi\Service\WordpressClient
     */
    private $client;

    private $idMap = [];

    public function __construct(WordpressClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $idMap
     *
     * @return self
     */
    public function setIdMap(array $idMap)
    {
        $this->idMap = $idMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getIdMap()
    {
        return $this->idMap;
    }



    public function __invoke(EventInterface $event)
    {
        $result = null;

        switch ($event->getName()) {
            default:
            case 'wordpress-page':
                $id = $event->getParam('id');
                if ($id) {
                    if (isset($this->idMap[$id])) {
                        $id = $this->idMap[$id];
                    }
                    $result = $this->client->getPage($id);
                }
                break;

        }

        if ($key = $event->getParam('key')) {
            foreach (explode('.', $key) as $partKey) {
                if (isset($result->$partKey)) {
                    $result = $result->$partKey;
                } else {
                    $result = '';
                    break;
                }
            }
            return ['content' => $result];
        }

        if ($template = $event->getParam('template')) {
            return ['template' => $template, 'values' => ['result' => $result]];
        }

        return null;
    }
}