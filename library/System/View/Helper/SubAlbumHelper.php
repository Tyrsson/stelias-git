<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * SubAlbumHelper helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_SubAlbumHelper
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function subAlbumHelper ($paginator, $pageNumber)
    {
        
        return 'Subalbums Go here';
    }

    /**
     * Sets the view field
     * 
     * @param $view Zend_View_Interface            
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
