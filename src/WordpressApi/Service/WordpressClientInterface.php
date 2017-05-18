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

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
interface WordpressClientInterface 
{

    public function getPosts(array $args = []);
    public function getPost($id, array $args = []);
    public function getPostBySlug($slug, array $args = []);

    public function getPages(array $args = []);
    public function getPage($id, array $args = []);
    public function getPageBySlug($slug, array $args = []);

}