<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * TreeDigit helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_TreeDigit
{

    /**
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     */
    public function treeDigit ()
    {
        // TODO Auto-generated System_View_Helper_TreeDigit::treeDigit() helper
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
