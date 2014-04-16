<?php

/**
 * Admin_AdminSkinsController
 *
 * @author Joey Smith
 * @version 0.9.1
 */

require_once 'Zend/Controller/Action.php';
class Admin_AdminSkinsController extends Dxcore_Controller_AdminAction {

	public $installer;

	public function init() {
		parent::init();
		$this->installer = new Dxcore_SkinInstaller();
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {

	}
// 	public function installSkinAction() {
// 		$form = new Admin_Form_UploadSkin();
// 		if($this->_request->isPost()) {
// 			if($form->isValid($this->_request->getPost())) {
// 				$data = $form->getValues();
// 				//Zend_Debug::dump($data);
// 				$this->installer->setArchive($data['files']);
// 				try {
// 					$this->installer->install();
// 				} catch (Exception $e) {
// 					echo $e->getMessage();
// 				}
// 				//$status = $this->installer->install();
// 			}
// 		}
// 		$this->view->form = $form;
// 	}
	public function settingsAction()
	{
	    $form = new Admin_Form_SkinSettings();
	    $skins = new Admin_Model_Skins();

	    //Zend_Debug::dump($skins->fetchCurrent());

	    if($this->_request->isPost()) {
	        if($form->isValid($this->_request->getPost())) {

	            $current = $skins->fetchCurrent();

	            $data = $form->getValues(true);
	            if($current->skinId == $data['skin']) {
	                // do nothing
	            } else {
	                $current->isCurrentSkin = 0;
	                $current->save();

	                $new = $skins->fetchById((int)$data['skin']);
	                $new->isCurrentSkin = 1;
	                $new->save();

	            }

	            //Zend_Debug::dump($data);
	        }
	    }
	    $this->view->form = $form;
	}
}
