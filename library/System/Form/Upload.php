<?php

Class System_Form_Upload extends Zend_Form {

    public function init($destination = null) {

        
        $element = new Zend_Form_Element_File('file');
        $element->setLabel('Upload a file:')
                //The following must be set to a valid writable path
                ->setDestination($destination);
                
// Ensure only 1 file
        $element->addValidator('Count', false, 1);
// Limit to 100K
        $element->addValidator('Size', false, 2097152);
// Allow only ext's in the list
        $element->addValidator('Extension', false, 'jpg,png,gif,psd,txt,rtf,doc,docx,xls,xlsx,xlsm,,wks,ods,ots,xlr,tsv,csv,odt,zip,bmp,7z,bz2,tar,tar.gz');

        $submit = new Zend_Form_Element_Submit('upload');
        $submit->setLabel('Upload')
                ->setOptions(array('class' => 'submit'));

        $this->addElement($element)
                ->addElement($submit);
    }
//     public function loadDefaultDecorators() 
//     {
//         $this->setDecorators(array(
//             'FormElements',
//             'Fieldset',
//             'Form'
//         ));
//     }

}
