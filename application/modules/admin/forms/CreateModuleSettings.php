<?php
class Admin_Form_CreateModuleSettings extends Dxcore_Form_Base
{
	public function init() {
	    $moduleName = new Zend_Form_Element_Text('moduleName');
		$moduleName->setLabel('Module Name')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$variable = new Zend_Form_Element_Text('variable');
		$variable->setLabel('Variable Name')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$value = new Zend_Form_Element_Text('value');
		$value->setLabel('Value')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$settingType = new Zend_Form_Element_Text('settingType');
		$settingType->setLabel('Setting Type')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit')
				->setOptions(array('class' => 'submit'));

		$this->addElement($moduleName)
				->addElement($variable)
				->addElement($value)
				->addElement($settingType)
		        ->addElement($submit);
	}

}