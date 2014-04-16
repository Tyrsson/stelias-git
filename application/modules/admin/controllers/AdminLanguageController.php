<?php

/**
 * Admin_AdminSkinsController
 *
 * @author Joey Smith
 * @version 0.9.1
 */

require_once 'Zend/Controller/Action.php';
class Admin_AdminlanguageController extends Dxcore_Controller_AdminAction {



	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$settings = new Admin_Model_Language();
		if ($this->_request->isPost ()) {
			$form = $settings->getFormWithoutValues ();
			if ($form->isValid ( $this->_request->getPost () )) {
				$data = $form->getValues ();
				foreach ( $data as $key => $value ) {
					$row = $settings->fetchVar ( $key );
					$row->langText = $value;
					$row->save ();
				}
			}
			$this->view->form = $form;
		} else {
			$this->view->form = $settings->getFormWithValues ();
		}
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
	public function addAction() 
	{
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'lang', 'field' => 'langKey'));
		$form = new Admin_Form_AddLanguage();
		if ($this->_request->isPost()) {
			if($form->isValid($this->_request->getPost())) {
				$data = $form->getValues();
				if($validator->isValid($data['langKey'])) {
					
					try {
						$lang = new Admin_Model_Language();
						$row = $lang->fetchNew();
						
						$row->setFromArray($data);
						$result = (bool)$row->save();
						if($result) {
							$this->messenger->addMessage('Language string created.');
							$this->redirect('/admin/success');
						}
					} catch (Exception $e) {
						$this->message = $e->getMessage();
						echo $e->getMessage();
					}
				} else {
					$messages = $validator->getMessages();
					foreach($messages as $message) {
						echo "$message\n";
					}
				}
				
			}
		}
		$this->view->form = $form;
	}
}
