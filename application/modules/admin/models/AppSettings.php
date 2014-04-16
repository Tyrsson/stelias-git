<?php
/**
 * AppSettings
 *
 * @author Joey Smith
 * @version 0.1
 */
require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_AppSettings extends Zend_Db_Table_Abstract {

	protected $_name = 'appsettings';
	protected $_primary = 'variable';
	protected $_sequence = false;
	protected $_rowsetClass = 'Admin_Model_Rowset_AppSettings';
	public    $form;

	public function init() {
		$this->form = new Zend_Form();
		$this->form->setName('appSettingsForm');
	}
	public function fetchVar($variable) {
		$query = $this->select()
		->from( $this->_name, array ('variable', 'value') )
		->where ( 'variable = ?', $variable );
		$result = $this->fetchRow ( $query );
		return $result;
	}
	public function fetch() {
		$settings = $this->fetchAll();
		return $settings;
	}
	public function getForm() {
	    return $this->form;
	}
	public function getValues() {
		return $this->form->getValues();
	}
	public function getFormWithValues() {
		$baseElement = 'Zend_Form_Element_';
		foreach($this->fetch() as $settingArray) {
			// construct the class name string
			if(!empty($settingArray->settingType) || $settingArray->settingType !== '') {
				$elementName = $baseElement . $settingArray->settingType;
				// call the form element class
				$element = new $elementName($settingArray->variable);
				$element->setLabel($settingArray->variable);
				$element->addFilter ( 'StringTrim' );
				$element->setValue($settingArray->value);
				if($settingArray->settingType === 'Textarea') {
					$element->setAttribs(array('cols' => '40', 'rows' => '4'));
				}
	
				$this->form->addElement($element);
			}
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->form->addElement($submit);
		return $this->form;
	}
	public function getFormWithoutValues() {
		$baseElement = 'Zend_Form_Element_';
		foreach($this->fetch() as $settingArray) {
			// construct the class name string
			$elementName = $baseElement . $settingArray->settingType;
			// call the form element class
			$element = new $elementName($settingArray->variable);
			$element->setLabel($settingArray->variable);
			$element->addFilter ( 'StringTrim' );
			if($settingArray->settingType === 'Textarea') {
				$element->setAttribs(array('cols' => '40', 'rows' => '4'));
			}
			$this->form->addElement($element);
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->form->addElement($submit);
		return $this->form;
	}
	public function buildForm() {
	$baseElement = 'Zend_Form_Element_';
	    foreach($this->fetch() as $settingArray) {
	            // construct the class name string
	        	$elementName = $baseElement . $settingArray->settingType;
	            // call the form element class
	            $element = new $elementName($settingArray->variable);
	            $element->setLabel($settingArray->variable);
	            $element->addFilter ( 'StringTrim' );
	            $element->setValue($settingArray->value);
	            if($settingArray->settingType === 'Textarea') {
	                $element->setAttribs(array('cols' => '40', 'rows' => '4'));
	            }
	        $this->form->addElement($element);
	    }
	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setLabel('Submit');
	    $this->form->addElement($submit);
	    return $this->form;
	}
}