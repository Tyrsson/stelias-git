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
class Dxcore_View_Helper_SiteStyles extends Zend_View_Helper_Abstract
{
    public $adminCssPath;
    /**
     * @var Zend_View_Interface
     */
    public $view;
    /**
     *
     */
    public function loadFluid()
    {
        $this->view->headLink()->prependStylesheet('/skins/fluid/reset.css', 'screen');
        //$this->view->headLink()->appendStylesheet('/skins/fluid/text.css', 'screen');
        //$this->view->headLink()->appendStylesheet('/skins/fluid/grid.css', 'screen');
        //$this->view->headLink()->appendStylesheet('/skins/fluid/nav.css', 'screen');
        $this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/nav.css', 'screen');
        //$this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/layout.css', 'screen');

        switch(IS_MOBILE) {
        	case true:
        		//$this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/mobile.css', 'screen');
        		break;
        	case false :
        	default:
        		//$this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/style.css', 'screen');
        		break;
        }

        $this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/style.css', 'screen');
        
        $this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/printable.css', 'screen');

        $this->view->headLink()->appendStylesheet('/skins/'.$this->skin->skinName.'/print.css', 'print');

        if(isset($this->skin->skinName) && file_exists('skins/'.$this->skin->skinName.'/images/favicon.ico'))
        {
            $favicon = '/skins/'.$this->skin->skinName.'/images/favicon.ico';
        } else {
            $favicon = '/skins/default/images/favicon.ico';
        }
        $this->view->headLink(array('rel' => 'icon', 'href' => $favicon));


        // conditionals for ie
        $this->view->headLink()->appendStylesheet('/skins/fluid/ie6.css', 'screen', 'IE 6', null);
        $this->view->headLink()->appendStylesheet('/skins/fluid/ie.css', 'screen', 'IE 7', null);
        $this->view->headLink()->appendStylesheet('/js-src/jquery-ui/css/smoothness/jquery-ui-1.10.2.custom.css', 'screen');
        $this->view->headLink()->appendStylesheet('/js-src/jquery-ui/plugins/css/jquery-ui-timepicker.css', 'screen');
        $jquery = $this->view->jQuery();
        $jquery->enable();
        $jquery->uiEnable();

        if($this->view->isAdmin === true) :
            //$this->view->headLink()->prependStylesheet('/skins/'.$this->skin->skinName.'/jq-aurora/jquery-ui-1.8.23.custom.css', 'screen');


                //$this->view->headLink()->appendStylesheet($this->adminCssPath, 'screen');

        endif;


        $this->view->headScript()->appendFile('/js-src/jquery-fluid16.js', 'text/javascript', null);
        $this->view->headScript()->appendFile('/js-src/jquery-calc.js', 'text/javascript', null);
        $this->view->headScript()->appendFile('/js-src/jquery-form.js', 'text/javascript', null);
        $this->view->headScript()->appendFile('/js-src/aurora.js', 'text/javascript', null);
        $this->view->headScript()->appendFile('/js-src/jquery-ui/plugins/timepicker.js', 'text/javascript', null);
    }
    public function siteStyles ()
    {
        $this->adminCssPath = '/skins/default/admin.css';
        /**
         * For local building/trouble shooting assign this var as the sites domain name
         *
         */

        	$request = Zend_Controller_Front::getInstance()->getRequest();
        	$skins = new Admin_Model_Skins();
        	$skin = $skins->fetchCurrent();
        	$this->skin = $skin;

        	if(isset($skin->skinName)) {
        	    switch($skin->includeFluid) {
        	        case 1 :
        	            $this->loadFluid();
        	            return;
        	            break;

        	        case 0 :

        	            break;

        	        default:

        	            break;
        	    }
        	}

        	$this->view->headLink()->prependStylesheet('/skins/default/reset.css', 'screen');

        	// make sure if this is admin, load the admin style sheet for all modules
        	if($this->view->isAdmin === false)
        	{

        	    if( (isset($skin->skinName) && $skin->skinName == 'default') || (isset($skin->includeDefault) && $skin->includeDefault == 1))
        	    {
        	    	$file_uri = 'skins/default/style.css';
        	    	if (file_exists($file_uri)) {
        	        	$this->view->headLink()->appendStylesheet('/' . $file_uri);
        	    	}
        	    }
        	    if(isset($skin->skinName) && isset($skin->skinCssPath) && !empty($skin->skinCssPath) )
        	    {
        	        $this->view->headLink()->appendStylesheet('/' . $skin->skinCssPath);
        	    }


//         			$file_uri = 'skins/default/style.css';
//         			if (file_exists($file_uri)) {
//         	    		$this->view->headLink()->appendStylesheet('/' . $file_uri);
//         			}

        	}

        	if($this->view->isAdmin === true)
        	{
        	    $moduleName = 'admin';
	        	$file_uri = 'skins/default/' . $moduleName .'.css';
	        	if (file_exists($file_uri)) {
	        	    $this->view->headLink()->appendStylesheet('/' . $file_uri);
	        	}
        	}

        	if(isset($skin->skinName) && file_exists('skins/'.$skin->skinName.'/images/favicon.ico'))
        	{
        	    $favicon = '/skins/'.$skin->skinName.'/images/favicon.ico';
        	} else {
        	    $favicon = '/skins/default/images/favicon.ico';
        	}
        	$this->view->headLink(array('rel' => 'icon', 'href' => $favicon));


            $moduleName = $request->getModuleName();
	        $file_uri = 'skins/default/' . $moduleName .'.css';
	        //Zend_Debug::dump($file_uri);
	        if (file_exists($file_uri)) {
	            //$this->view->headLink()->appendStylesheet('/' . $file_uri, 'screen');
	        }
	        // Do we care about ie?
	        $ie_file_uri = 'skins/default/ie8.css';
	        if(file_exists($ie_file_uri)) {
	        	$this->view->headLink()->appendStylesheet('/' . $ie_file_uri, 'screen', 'IE 8', null);
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