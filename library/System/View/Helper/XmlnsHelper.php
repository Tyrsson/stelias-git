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
class System_View_Helper_XmlnsHelper extends Zend_View_Helper_Abstract
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
    public function xmlnsHelper ()
    {
    	$docType = $this->view->doctype()->getDoctype();
    	switch ($docType) {
    		case "XHTML1_TRANSITIONAL":
    			return 'xmlns="http://www.w3.org/1999/xhtml"';
    			break;
    		case "HTML5":
    			break;
    		case "XHTML1_RDFA":
    			return 'xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns="http://www.w3.org/1999/xhtml"';
    			break;
    		default:
    			return 'xmlns="http://www.w3.org/1999/xhtml"';
    			break;
    	}
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
