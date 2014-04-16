<?php
/**
 *
 * @author Joey
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * SeoHelper helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Dxcore_View_Helper_SiteName extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    /**
     * @var object appSettings
     */
    public $appSettings;
    /**
     * 
     */
    public function siteName ()
    {      
        $this->appSettings = Zend_Registry::get('appSettings');
    	return $this->view->headTitle($this->appSettings->siteName);
    }

    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
