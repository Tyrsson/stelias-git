<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * ChildPages helper
 *
 * @uses viewHelper Dxcore_View_Helper
 */
class Dxcore_View_Helper_ChildPages
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function childPages ()
    {
        
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
