<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * SubPageNav Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_SubPageNav extends Zend_Controller_Action_Helper_Abstract
{
    protected $_container;
    public $appSettings;
    public $nav;
    public function __construct()
    {
        $this->nav_container = new Zend_Navigation();
    }
    public function renderNav($subPages)
    {
        $i = 2;
        foreach ($subPages as $page) {
            $pages[] = Zend_Navigation_Page::factory(array(
                    'label' => ucfirst($page->pageName),
                    'id'    => $page->pageUrl,
                    'uri'   => '/' . $page->pageUrl,
                    'resource' => 'page',
                    'privilege' => "page.$page->role.view",
                    'order' => ($page->pageOrder !== null) ? $page->pageOrder : $i,
            )
            );
        }
        $i++;
        $links = '<a href="/testing">Test Link</a>';


        $this->nav_container->addPages($pages);

        return $this->nav_container;
       // return $links;
    }
    public function direct($subPages)
    {
        return $this->renderNav($subPages);
    }
}
