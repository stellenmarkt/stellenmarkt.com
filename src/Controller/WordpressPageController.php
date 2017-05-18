<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Gastro24\WordpressApi\Service\WordpressClientInterface;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressPageController extends AbstractActionController
{
    /**
     *
     *
     * @var WordpressClientInterface
     */
    private $client;

    public function __construct(WordpressClientInterface $client)
    {
        $this->client = $client;
    }

    public function indexAction()
    {
        $type = $this->params()->fromRoute('type', 'page');
        $id = $this->params()->fromRoute('id');

        if (!is_numeric($id) || 0 === strpos($id, '~')) {
            $article = 'page' == $type ? $this->client->getPageBySlug($id) : $this->client->getPostBySlug($id);
        } else {
            $article = 'page' == $type ? $this->client->getPage($id) : $this->client->getPost($id);
        }

        if (isset($article->error) && $article->error) {
            $this->getResponse()->setStatusCode(404);
            return [
                'message' => sprintf('[%s] %s', $article->code, $article->message)
            ];
        }
        return [ 'article' => $article ];
    }
}