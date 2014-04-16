<?php
class Pages_Form_CreateCategory extends Zend_Form
{
    public function init() {

        // album name
        $name = new Zend_Form_Element_Text('categoryName');
        $name->setLabel('Category Name:')
                     ->setRequired(true)
                     ->addValidator('NotEmpty', true)
                     ->addFilter('HtmlEntities')
                     ->addFilter('StringTrim');
        
        //Create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit')
        ->setOptions(array('class' => 'submit'));
        
        $this->addElement($name)
        	 ->addElement($submit);
    }
}