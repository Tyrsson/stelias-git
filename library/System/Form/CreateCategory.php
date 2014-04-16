<?php

class System_Form_CreateCategory Extends Zend_Form 
{
	public function init() {

    	$cats = new System_Model_Categories();
    	
    	$element = new System_Form_Element_ImageFile('catImage');
    	$element->setLabel('Upload a file:')
    	//The following must be set to a valid writable path
    	->setDestination($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'categories')
    	;
    	
    	// Ensure only 1 file
    	$element->addValidator('Count', false, 1);
    	// Limit to 100K
    	$element->addValidator('Size', false, 2097152);
    	// Allow only ext's in the list
    	$element->addValidator('Extension', false, 'jpg,png,gif');
    	
        $catName = new Zend_Form_Element_Text('catName');
        // create text input for name
        $catName->setLabel('Category Name:')
        //->setOptions(array('size' => '30'))
        ->setRequired(true)
        ->addValidator('NotEmpty', true)
        ->setOptions(array('class' => 'input'))
        //->addValidator('Alpha', 'allowWhiteSpace', true)
        //->addFilter('HtmlEntities')
        ->addFilter('StringTrim');
        
        $parentCat = new Zend_Form_Element_Select('parentId');
        $parentCat->setLabel('Parent Category:')->setMultiOptions($cats->fetchDropDown()->toArray());
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        
        $this->addElement($catName)
             //->addElement($parentCat)
             ->addElement($element)
             ->addElement($submit);
    }
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
        'FormElements',
        
        'Form'
        ));
    }
}
