<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Bulletin helper
 *
 * @uses viewHelper Stelias_View_Helper
 */
class Stelias_View_Helper_Bulletin
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function bulletin ()
    {
        // TODO Auto-generated Stelias_View_Helper_Bulletin::bulletin() helper
        return null;
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
