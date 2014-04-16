<?php
class Admin_Form_AddLanguage extends Dxcore_Form_Base
{
	public function init() {
// 	    $moduleName = new Zend_Form_Element_Text('');
// 		$moduleName->setLabel('Module Name')
// 		->setRequired(true)
// 		->addValidator('NotEmpty', true)
// 		->addFilter('StringTrim');

		$key = new Zend_Form_Element_Text('langKey');
		$key->setLabel('Language String')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$text = new Zend_Form_Element_Text('langText');
		$text->setLabel('Text')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');
		
		$locale = new Zend_Form_Element_Text('locale');
		$locale->setLabel('Locale')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit')
				->setOptions(array('class' => 'submit'));

		$this->addElement($key)
				->addElement($text)
				->addElement($locale)
		        ->addElement($submit);
	}

}