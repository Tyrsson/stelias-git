<?php

/**
 * Media_AdminSliderController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
class Media_AdminSliderController extends Dxcore_Controller_AdminAction {
	
	public $slider;
	
	public function init() 
	{
		parent::init();
		$model = new Media_Model_SliderSettings();
		$this->slider = $model->fetchSettings();
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$form = new Media_Form_CreateSlider();
		
		if($this->_request->isXmlHttpRequest()) {
			$this->getHelper('viewRenderer')->setNoRender(true);
			$this->_helper->layout->disableLayout();
		}
		if($this->_request->isPost()) {
			if($form->isValid($this->_request->getPost()))
			{
				$this->post = $form->getValues();
				if($this->post['name'] !== 'default')
					$this->post['name'] = 'default';
				
				$this->slider->setFromArray($this->post);
				try {
					$this->slider->save();
					$this->messenger->addMessage('Settings Saved!');
				} catch (Zend_Db_Table_Row_Exception $e) {
					$this->messenger->addMessage('Settings could not be saved!');
				}
				
			}
		} else {
			$form->populate($this->slider->toArray());
		}
		$this->view->form = $form;
	}
	
}