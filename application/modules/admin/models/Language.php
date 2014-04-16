<?php

/**
 * Language
 *
 * @author jsmith
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_Language extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'lang';
	protected $_primary = 'langKey';
	protected $_sequence = false;

	public function init() {
		$this->form = new Zend_Form();
		$this->form->setName('langForm');
	}
	public function fetchVar($langKey) {
		$query = $this->select()
		->from( $this->_name, array ('langKey', 'langText') )
		->where ( 'langKey = ?', $langKey );
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
		$baseElement = 'Zend_Form_Element_Text';
		foreach($this->fetchAll() as $lang) {
				// call the form element class
				$element = new $baseElement($lang->langKey);
				$element->setLabel($lang->langKey);
				$element->addFilter ( 'StringTrim' );
				$element->setValue($lang->langText);
				$this->form->addElement($element);
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->form->addElement($submit);
		return $this->form;
	}
	public function getFormWithoutValues() {
		$baseElement = 'Zend_Form_Element_Text';
		foreach($this->fetchAll() as $lang) {
			// call the form element class
			$element = new $baseElement($lang->langKey);
			$element->setLabel($lang->langKey);
			$element->addFilter ( 'StringTrim' );

			$this->form->addElement($element);
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->form->addElement($submit);
		return $this->form;
	}
	public function buildForm() {
		$baseElement = 'Zend_Form_Element_Text';
		foreach($this->fetchAll() as $lang) {
			// call the form element class
			$element = new $baseElement($lang->langKey);
			$element->setLabel($lang->langKey);
			$element->addFilter ( 'StringTrim' );
			$element->setValue($lang->langText);

			$this->form->addElement($element);
		}
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');
		$this->form->addElement($submit);
		return $this->form;
	}

}
