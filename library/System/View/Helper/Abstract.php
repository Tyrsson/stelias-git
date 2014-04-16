<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Abstract helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_Abstract extends Zend_View_Helper_Abstract
{
    /**
     * View object
     *
     * @var Zend_View_Interface
     */
    public $view;
    /*
     * @var Admin_Model_Settings
     */
    public $appSettings;
    /*
     * @var string | html to return
     */
    public $html;
    
    public function __construct()
    {
        $this->appSettings = Zend_Registry::get('appSettings');
        $this->html = "";
    }
    
    /**
     * Set the View object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_Abstract
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
    
    /**
     * Strategy pattern: currently unutilized
     *
     * @return void
     */
    public function direct()
    {
    }
}
