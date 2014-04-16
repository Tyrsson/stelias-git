<?php
/**
 * AdminAdminController
 *
 * @author Joey Smith
 * @version 0.1
 */
require_once 'Dxcore/Controller/AdminAction.php';
class Admin_AdminSettingsController extends Dxcore_Controller_AdminAction {
	public $appSettings;
	public $moduleSettings;
	public function init() {
		parent::init ();
		$this->appSettings = new Admin_Model_SettingsGateway ();
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated AdminAdminController::indexAction() default
	}
	public function settingsAction() {
		$settings = new Admin_Model_AppSettings ();
		if ($this->_request->isPost ()) {
			$form = $settings->getFormWithoutValues ();
			if ($form->isValid ( $this->_request->getPost () )) {
				$data = $form->getValues ();
				foreach ( $data as $key => $value ) {
					$row = $settings->fetchVar ( $key );
					$row->value = $value;
					$row->save ();
				}
			}
			$this->view->form = $form;
		} else {
			$this->view->form = $settings->getFormWithValues ();
		}
	}
	public function modulesettingsAction() {
		$settings = new Admin_Model_ModuleSettings ();
		$settings->init ( isset ( $this->_request->modName ) ? $this->_request->modName : '' );
		if (isset ( $this->_request->modName )) {
			if ($this->_request->isPost ()) {
				$form = $settings->getFormWithoutValues ();
				if ($form->isValid ( $this->_request->getPost () )) {
					$data = $form->getValues ();
					foreach ( $data as $key => $value ) {
						$row = $settings->fetchVar ( $this->_request->modName, $key );
						$row->value = $value;
						$row->save ();
					}
				}
				$this->view->form = $form;
			} else {
				$this->view->form = $settings->getFormWithValues ();
			}
		}
	}
	public function addmodsettingsAction() {
	    $settings = new Admin_Model_ModuleSettings();
	    $form = new Admin_Form_CreateModuleSettings();
	    
	    if($this->_request->isPost())
	    {
	        if($form->isValid($this->_request->getPost()))
	        {
	            $data = $form->getValues();
	            $row = $settings->fetchNew();
	            $row->setFromArray($data);
	            $conf = (bool) $row->save();
	            if($conf) {
	                $this->messenger->addMessage('Your setting was created');
	            } else {
	                throw new Dxcore_Application_Exception('Setting not created, please check your syntax!!');
	            }
	        }
	    } 
	    $this->view->form = $form;
	}
}