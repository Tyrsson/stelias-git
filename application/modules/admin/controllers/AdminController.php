<?php

/**
 * AdminAdminController
 * 
 * @author
 * @version 
 */

require_once 'Dxcore/Controller/AdminAction.php';

class Admin_AdminController extends Dxcore_Controller_AdminAction {
	
	
	public function init()
	{
		parent::init();
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() 
	{

	}
	public function successAction() {
		$params = $this->_request->getParams();
		if(isset($params['deleted'])) {
		    echo $params['deleted'] . ' records deleted!';
		}
	}
}
