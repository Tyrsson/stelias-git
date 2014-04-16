<?php
class Pages_Form_Comment extends Zend_Dojo_Form
{
	public function init()
	{
		$this->setMethod('post');
		//$this->setOptions(array('class' => 'tundra'));

		$hidden = new Zend_Form_Element_Hidden('name');

		$name = new Zend_Dojo_Form_Element_ComboBox('pageName');
		$name->setLabel('Page Name');
		$name->setOptions(array('autocomplete' => false,
								'storeId' => 'nameStore',
								'storeType' => 'dojo.data.ItemFileReadStore',
								'storeParams' => array('url' => "/comment/ajaxpagename"),
								'dijitParams' => array('searchAttr' => 'name')))
									->setRequired(true)
									->addValidator('NotEmpty', true)
									->addFilter('HtmlEntities')
									->addFilter('StringToLower')
									->addFilter('StringTrim');
		$comment = new Zend_Dojo_Form_Element_Editor('comment');
		$comment->setLabel('Comment:')
				->setOptions(array('width' => '350px',
									'height' => '200px'));

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel('Submit');

		$this->addElement($hidden)
			 ->addElement($name)
			 ->addElement($comment)
			 ->addElement($submit);
	}
}