<?php
/**
 *
 * @author jsmith
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * ClientCache helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_ClientCache {
	
	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;
	
	/**
	 */
	public function clientCache() {
		// TODO Auto-generated System_View_Helper_ClientCache::clientCache()
		// helper
		return null;
	}
	
	/**
	 * Sets the view field
	 * 
	 * @param $view Zend_View_Interface        	
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
