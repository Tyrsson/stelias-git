<?php

/**
 * AdminController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';
class Installer_AdminController extends Dxcore_Controller_AdminAction {
	public function init(){
		parent::init();
		switch($this->appSettings->isInstalled) {
			case true :
				throw new Zend_Application_Exception('You can only install once. Please open a support ticket for assistance.');
				break;
			case false :

				break;
		}

	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated AdminController::indexAction() default action
	}
	public function step1Action() {
		// step 1
	}
	public function step2Action() {
		// step 2
	}
	public function step3Action() {
		// step 3
	}
	public function installskinAction() {

		$handler = new Installer_Service_Skin();
		Zend_Debug::dump($handler);
		
		
		
		// old code
		$this->installer = new Installer_Service_SkinInstaller();
		$form = new Installer_Form_UploadSkin();
		if($this->_request->isPost()) {
			if($form->isValid($this->_request->getPost())) {
				$data = $form->getValues();
				//Zend_Debug::dump($data);
				$this->installer->setArchive($data['files']);
				try {
					$this->installer->install();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				//$status = $this->installer->install();
			}
		}
		$this->view->form = $form;
	}
}
