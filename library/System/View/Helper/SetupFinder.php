<?php
/**
 *
 * @author Laptop
 * @version
 */
require_once 'Zend/View/Interface.php';
/**
 * SetupEditor helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class System_View_Helper_SetupFinder
{
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     *
     */
    function setupFinder ($resourceType)
    {

    	//$this->view->headScript()->appendFile('/js-src/ckeditor/ckeditor.js');


    		$this->view->headScript()->appendFile('/js-src/ckfinder/ckfinder.js');

    	$finderPath = '/js-src/ckfinder/';
    	return "<script type=\"text/javascript\">
        			var finder = new CKFinder();
    			    finder.basePath = '" . $finderPath . "';
    			    finder.resourceType = '" . $resourceType . "';
    			    finder.maxSimultaneousUploads = 0;
    			    finder.create();
        		</script>";
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
