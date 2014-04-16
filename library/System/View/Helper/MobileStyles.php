<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';
/**
 * SiteStyles helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class System_View_Helper_MobileStyles extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     *
     */
    public function mobileStyles ()
    {
        /**
         * For local building/trouble shooting assign this var as the sites domain name
         *  $serverName = 'test.com'; Would load /css/test.com.css file as the css file loaded
         */

        	$request = Zend_Controller_Front::getInstance()->getRequest();
        	
        	$skins = new Admin_Model_Skins();
        	$skin = $skins->fetchCurrent();
        	
        	$this->view->headLink()->prependStylesheet('skins/default/reset.css', 'screen');

        	// make sure if this is admin, load the admin style sheet for all modules
        	if($this->view->isAdmin === false)
        	{
        	    
        		if( (isset($skin->skinName) && $skin->skinName == 'default') || (isset($skin->includeDefault) && $skin->includeDefault == 1) )
        	    {
        	        $file_uri = 'skins/default/style.mobi.css';
        	        if (file_exists($file_uri)) {
        	            $this->view->headLink()->appendStylesheet('/' . $file_uri);
        	        }
        	    }
        	    if(isset($skin->skinName) && isset($skin->skinCssMobiPath) && !empty($skin->skinCssMobiPath) )
        	    {
        	        $this->view->headLink()->appendStylesheet('/' . $skin->skinCssMobiPath);
        	    }
        	    
        	    
        	}

        	if($this->view->isAdmin === true)
        	{
        	    $moduleName = 'admin';
	        	$file_uri = 'skins/default/' . $moduleName .'.mobi.css';
	        	if (file_exists($file_uri)) {
	        	    $this->view->headLink()->appendStylesheet('/' . $file_uri);
	        	}
        	}
        	
        	if(isset($skin->skinName) && file_exists('skins/'.$skin->skinName.'/images/favicon.ico'))
        	{
        	    $favicon = 'skins/'.$skin->skinName.'/images/favicon.ico';
        	} else {
        	    $favicon = 'skins/default/images/favicon.ico';
        	}
        	$this->view->headLink(array('rel' => 'icon', 'href' => $favicon));

            $moduleName = $request->getModuleName();
	        $file_uri = 'skins/default/' . $moduleName .'.mobi.css';
	        //Zend_Debug::dump($file_uri);
	        if (file_exists($file_uri)) {
	            //$this->view->headLink()->appendStylesheet('/' . $file_uri, 'screen');
	        }
	        // Do we care about ie?
	        $ie_file_uri = 'skins/default/' . $moduleName .'.mobi.ie.css';
	        if(file_exists($ie_file_uri)) {
	        	$this->view->headLink()->appendStylesheet('/' . $ie_file_uri, 'screen', 'IE 9', null);
	        }
	        return $this->view->headLink();
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
