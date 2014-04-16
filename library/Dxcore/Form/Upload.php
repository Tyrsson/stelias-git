<?php

Class Dxcore_Form_Upload extends Zend_Form {

    public function init() {

        Zend_Dojo::enableForm($this);
        $this->setMethod('post');
        $this->setAttrib('data-dojo-type', 'dojox/form/Manager');
        $this->setAttrib('id', 'form');
        
        $element = new Zend_Form_Element_File('file');
        $element->setLabel('Upload a file:');
                
        $element->addValidator('Count', false, array('min' => 1, 'max' => 3));
// Limit to 100K
        $element->addValidator('Size', false, 2097152);
// Allow only ext's in the list
        $element->addValidator('Extension', false, 'jpg,png,gif,psd,txt,rtf,doc,docx,xls,xlsx,xlsm,,wks,ods,ots,xlr,tsv,csv,odt,zip,bmp,7z,bz2,tar,tar.gz');

        $submit = new Zend_Form_Element_Submit('upload');
        $submit->setLabel('Upload')
                ->setAttrib('type', 'submit')
                ->setAttrib('submit', 'return false')
                ->setOptions(array('class' => 'submit'));

        $this->addElement($element)
                ->addElement($submit);
    }
    public function loadDefaultDecorators() 
    {
        $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form'
        ));
    }

}
