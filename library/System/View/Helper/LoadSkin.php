<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * LoadSkin helper
 *
 * @uses viewHelper System_View_Helper
 */

class System_View_Helper_LoadSkin extends System_View_Helper_Abstract {

	public $options;
	public $skin;
	public $cSkin;
	public $cssBasePath = '/skins';
	public $cssDefaultPath = '/default';
	public $cssAdminPath = '/admin';
	public $cssTargetPath = '';
	public $includeDefault = false;
	public $device;
	/*
	 * @var bool $isAdmin - Are we loading for the admin layout?
	 * @var $customLayout - Are we loading for something other than layout.phtml, or admin.phtml ?
	 * This allows to skip the loading of the skin files to allow the layout to load them
	 */
	public function loadSkin(Admin_Model_Row_Skin $skin, array $options = array(), $isAdmin = false, $customLayout = false) {

		//$isAdmin = true;

		/*
		 * This allows setting the options from the db row,
		* and still allows for call time override,
		* If $options['key'] matches a db column name
		* the $option['key'] value will be used.
		*/

	    $skinSettings = $skin->findDependentRowset('Admin_Model_SkinSettings', 'SkinSettings');
	    $index = count($skinSettings);
	    $customAdmin = false;
	    for ($i = 0; $i < $index; $i++) {
	    	if($skinSettings[$i]->spec === 'customAdmin') {
	    	    $customAdmin = $skinSettings[$i]->spec;
	    	}
	    }

		$this->setOptions(array_merge($skin->toArray(), $options));
		//Zend_Debug::dump($this->options);
		if($customLayout) {
			return; // in this condition, the layout will handle all of this since its custom
		}
		switch(true) {
			case (!$this->view->jQuery()->isEnabled() || !$this->view->jQuery()->uiIsEnabled()) :
				$this->view->jQuery()->enable();
				$this->view->jQuery()->uiEnable();
				break;
		}
		switch($this->view->scheme) {
			case 'https' :
				$this->view->jQuery()->setCdnSsl(true);
				break;
		}
		// default - custom skin path setup
		switch($skin->skinName) {
			case 'default' :
				$this->cssTargetPath = $this->cssBasePath . $this->cssDefaultPath;
				break;
			default :
				$this->cssTargetPath = $this->cssBasePath . '/' . $skin->skinName;
				break;
		}
		$this->view->skinName = $skin->skinName;
		// load the admin, or a custom admin based on the custom skin settings, or $this->options['customAdmin']
		if($isAdmin) {
			if( (isset($this->options['customAdmin']) && $this->options['customAdmin'] === true) || ( (bool) $customAdmin === true ) ) {
				$this->cssTargetPath .= $this->cssAdminPath;
			}
			elseif(!isset($this->options['customAdmin']) || $this->options['customAdmin'] === false) {
				$this->cssTargetPath = $this->cssBasePath . $this->cssDefaultPath . $this->cssAdminPath;
			}
		}

		$this->cssTargetPath .= '/';

		$this->view->headLink()->offsetSetStylesheet(1, $this->view->baseUrl() . $this->cssBasePath . $this->cssDefaultPath . '/reset.css', 'screen');
		if(!$isAdmin) {
		  $this->view->headLink()->offsetSetStylesheet(2, $this->view->baseUrl() . $this->cssTargetPath . 'jquery-ui/' . APPLICATION_ENV . '.css', 'screen');
		}
		if(isset($this->options['loadSilkIcons']) && $this->options['loadSilkIcons'] === true) {
			$this->view->headLink()->offsetSetStylesheet(3, $this->view->baseUrl() . '/js-src/jquery-ui/css/icons-silk/icons-silk.css', 'screen');
		}
		if(isset($this->options['loadTimePicker']) && $this->options['loadTimePicker'] === true) {
			//$this->view->headLink()->offsetSetStylesheet(4, $this->view->baseUrl() . '/js-src/jquery-ui/plugins/css/jquery-ui-timepicker.css', 'screen');

			/** ATTENTION the following is JScript, but it can still load here so we do not have to check this setting twice ;)
			 * JUST PAY ATTENTION TO THE FIRST ARGUMENT !!!!!!!!!!
			 */
			//$this->view->headScript()->offsetSetFile(2, $this->view->baseUrl() . '/js-src/jquery-ui/plugins/timepicker.js', 'text/javascript');
		}

		if(isset($this->options['overrideResponsive']) && $this->options['overrideResponsive'] === true) {
			$type = 'desktop';
		}
		elseif( !isset($this->options['overrideResponsive']) && $this->options['overrideResponsive'] === false || empty($this->options['overrideResponsive']) ) {
			$type = 'mobile';
		}

		$this->view->headLink()->offsetSetStylesheet(5, $this->view->baseUrl() . $this->cssBasePath . $this->cssDefaultPath . '/'. $type. '.css', 'screen');


		$this->view->headLink()->offsetSetStylesheet(6, $this->view->baseUrl() . $this->cssTargetPath . 'style.css', 'screen');
		// this will print the headlink in the layout, if you echo a call to this helper it will print TWICE !!!!!!!!!

		echo $this->view->headLink();

		// this is only here cause we use it some times ... but it still needs to be loaded before the jscript to prevent flashing ;)
		echo $this->view->headStyle();
		// End Style Loading

		// print this here so the following will stack after it
		if(!$isAdmin && $this->view->jQuery()->isEnabled() || $this->view->jQuery()->uiIsEnabled()) {
		    echo $this->view->jQuery();
		}
		elseif($isAdmin && $this->view->jQuery()->isEnabled() || $this->view->jQuery()->uiIsEnabled()) {
		    ZendX_JQuery_View_Helper_JQuery::enableNoConflictMode();
		    echo $this->view->jQuery();
		}
		// Start JS Loading

		// two is loaded above
		if(isset($this->options['loadAjaxedForms']) && $this->options['loadAjaxedForms'] === true) {
			//$this->view->headScript()->offsetSetFile(3, $this->view->baseUrl() . '/js-src/jquery-core/plugins/jquery-form.js', 'text/javascript');
		}
		$this->view->headScript()->offsetSetFile(4, $this->view->baseUrl() . '/js-src/jquery-core/plugins/jstree/jquery.jstree.js', 'text/javascript');
		$this->view->headScript()->offsetSetFile(5, $this->view->baseUrl() . '/js-src/jquery-core/plugins/jstree/tree.js', 'text/javascript');
		if(!$isAdmin) {
		  $this->view->headScript()->offsetSetFile(7, $this->view->baseUrl() . '/js-src/menu.js', 'text/javascript');
		}
		//$this->view->headScript()->offsetSetFile(8, $this->view->baseUrl() . '/js-src/ckeditor/ckeditor.js', 'text/javascript');
		//$this->view->headScript()->offsetSetFile(9, $this->view->baseUrl() . '/js-src/ckeditor/adapters/jquery.js', 'text/javascript');
		//$this->view->headScript()->offsetSetFile(10, $this->view->baseUrl() . '/js-src/ckfinder/ckfinder.js', 'text/javascript');


		// WE ALWAYS LOAD THE FOLLOWING LAST

		if($isAdmin) {
		    $this->view->headScript()->offsetSetFile(20, $this->view->baseUrl() . '/js-src/aurora.js', 'text/javascript');
		    //$this->view->headScript()->offsetSetFile(21, $this->view->baseUrl() . '/js-src/admin.js', 'text/javascript');
		}

		echo $this->view->headScript();


	}
	public function setOptions(array $options = array()) {
		//$this->options = $options;
		foreach($options as $optionKey => $optionValue)	 {
			$this->options[$optionKey] = $optionValue;
		}
	}
}
