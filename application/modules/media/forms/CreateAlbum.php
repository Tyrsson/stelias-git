<?php
class Media_Form_CreateAlbum extends Zend_Dojo_Form
{
	public function init()
	{
		$this->setMethod('post');
		$albumName = new Zend_Form_Element_Text('albumName');
		$albumName->setLabel('Album Name:');
		$albumName->setRequired(true);
		$albumName->addFilter('StringTrim')
					->addFilter('StringToLower')
					->addFilter('HtmlEntities')
					->addFilter('Alpha', array('allowwhitespace' => true));

		$role = new Zend_Dojo_Form_Element_ComboBox('role');
		$role->setLabel('Min Access Role (Type to search)');
		$role->setOptions(array('autocomplete' => false,
				'storeId' => 'roleStore',
				'storeType' => 'dojo.data.ItemFileReadStore',
				'storeParams' => array('url' => "/pages/json/rolestore"),
				'dijitParams' => array('searchAttr' => 'role')))
				->setRequired(true)
				->addValidator('NotEmpty', true)
				->addFilter('StringTrim');

		$passWord = $this->createElement('Password', 'passWord');
		$passWord->setLabel('Password:');
		//$passWord->setRequired(true);
		$passWord->addValidator(new Zend_Validate_StringLength(6, 12));
		$passWord->setAttrib('class', 'txt');

		$confirmPassword = $this->createElement('passWord', 'confirm_password');
		$confirmPassword->setLabel('Confirm Password:');
		//$confirmPassword->setRequired(true);
		$confirmPassword->addValidator(new Zend_Validate_StringLength(6, 12));
		$confirmPassword->addValidator(new Zend_Validate_Identical(Zend_Controller_Front::getInstance()->getRequest()->getParam('passWord')));

		$submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');

        $this->addElement($albumName)
        ->addElement($role)
        ->addElement($passWord)
        ->addElement($confirmPassword)
        ->addElement($submit);
	}
}