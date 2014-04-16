<?php

class System_Form_Submit extends Zend_Dojo_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
        // initialize form
        //$this->setAction('/news/submit')
             //->setMethod('post');
        //Drop down for page status
        $item_status = new Zend_Form_Element_Select('status');
        $item_status->setLabel('Status')->setMultiOptions(array(
                                                                '0' => 'Hidden', 
                                                                '1' => 'Published'
                                                               )
                                                         );
        $item_name = new Zend_Form_Element_Text('item_name');
        // create text input for name
        $item_name->setLabel('Item Name:')
                //->setOptions(array('size' => '30'))
                  ->setRequired(true)
                  ->addValidator('NotEmpty', true)
                  ->addValidator('Alpha', 'allowWhiteSpace', true)
                  ->addFilter('HTMLEntities')
                  ->addFilter('StringTrim');
        // create text input for message body
        $item_content = new Zend_Form_Element_Textarea('item_content');
        $item_content->setLabel('Item Content:')
                     ->setRequired(true)
                     ->addValidator('NotEmpty', true)
                     ->addFilter('StringTrim');
        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit Item')
               ->setOptions(array('class' => 'submit'));
        // attach elements to form
        $this->addElement($item_status)
             ->addElement($item_name)
             ->addElement($item_content)
             ->addElement($submit);
    }

//    public function loadDefaultDecorators() {
//        $this->setDecorators(array(
//            'FormElements',
//            'Fieldset',
//            'Form'
//        ));
//    }

}
