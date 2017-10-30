<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class Landingpages extends AbstractOptions
{

    private $idMap = [];

    private $queryMap = [];

    private $tabs = [];

    public function setFromArray($options)
    {
        $idMap = [];
        $queryMap = [];
        $tabs = [];

        foreach ($options as $term => $spec) {
            if (isset($spec['id'])) {
                $idMap[$term] = $spec['id'];
            }

            $queryMap[$term] = isset($spec['query']) ? $spec['query'] : [ 'q' => $term ];

            if (isset($spec['tab']) && isset($spec['panel'])) {
                $tabs[ $spec[ 'tab' ] ][ $spec[ 'panel' ] ][] = [$term, $spec[ 'text' ]];
            }

        }

        return parent::setFromArray(['idMap' => $idMap, 'queryMap' => $queryMap, 'tabs' => $tabs ]);
    }

    /**
     * @return array
     */
    public function getIdMap()
    {
        return $this->idMap;
    }

    /**
     * @param array $idMap
     *
     * @return self
     */
    public function setIdMap($idMap)
    {
        $this->idMap = $idMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryMap()
    {
        return $this->queryMap;
    }

    public function getQueryParameters($term)
    {
        return isset($this->queryMap[$term]) ? $this->queryMap[$term] : [];
    }

    /**
     * @param array $queryMap
     *
     * @return self
     */
    public function setQueryMap($queryMap)
    {
        $this->queryMap = $queryMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @param array $tabs
     *
     * @return self
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;

        return $this;
    }



}
