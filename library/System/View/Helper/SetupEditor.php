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
class System_View_Helper_SetupEditor
{
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     *
     */
    function setupEditor ($textareaId = 'content', $options = null, $withFinder = true, $resourceType)
    {

        //$this->view->headScript()->appendFile('/js-src/ckeditor/ckeditor.js');
        $this->view->headScript()->offsetSetFile(200, $this->view->baseUrl() . '/js-src/ckeditor/ckeditor.js', 'text/javascript');
        //This will need config values to use Finder
        if($withFinder === true )
        {

        }


        switch($withFinder) {
        	case true :
        	    $this->view->headScript()->offsetSetFile(-1, $this->view->baseUrl() . '/js-src/ckfinder/ckfinder.js', 'text/javascript');
        		$this->view->headScript()->appendFile('/js-src/ckfinder/ckfinder.js');
        		$finderPath = '/js-src/ckfinder/';
        		return "<script type=\"text/javascript\">
        			var editor = CKEDITOR.replace( '" . $textareaId . "' );
        			CKFinder.setupCKEditor( editor, '" . $finderPath . "', '" . $resourceType . "');
        		</script>";
        		break;
        	case false :
        		return "<script type=\"text/javascript\">
        			CKEDITOR.replace( '" . $textareaId . "' );
        		</script>";
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
