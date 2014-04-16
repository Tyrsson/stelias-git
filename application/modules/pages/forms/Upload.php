<?php
class Pages_Form_Upload extends Zend_Form {
    
    public function init() {
        
        $pages = new Pages_Model_Pages();
        
        $page = new Zend_Form_Element_Select('pageId');
        //self::getUserId();
        $page->setLabel('Upload to Page:')
        		->setMultiOptions($pages->fetchUserPages(self::getUserId())->toArray())
                ->setRequired(true);
        
        $files = new Zend_Form_Element_File('files');
        $files->setLabel('Upload Files:');
        //The following must be set to a valid writable path
        //->setDestination($upload->__setUploadPath($registry->get('job_id')));
        
        // Ensure only 1 file
        $files->addValidator('Count', array('min' => 1, 'max' => 5));
        // Limit to 100K
        $files->addValidator('Size', false, 9992097152);
        // Allow only ext's in the list
        $files->addValidator('Extension', false, 'jpg,png,gif,psd,txt,rtf,doc,docx,xls,xlsx,xlsm,,wks,ods,ots,xlr,tsv,csv,odt,zip,bmp,7z,bz2,tar,tar.gz,pdf');
        $files->setMultiFile(5);
        // end file 1
        
//         $element2 = new Zend_Form_Element_File('file2');
//         $element2->setLabel('Upload File 2:');
//         //The following must be set to a valid writable path
//         //->setDestination($upload->__setUploadPath($registry->get('job_id')));
        
//         // Ensure only 1 file
//         $element2->addValidator('Count', false, 2);
//         // Limit to 100K
//         $element2->addValidator('Size', false, 2097152);
//         // Allow only ext's in the list
//         $element2->addValidator('Extension', false, 'jpg,png,gif,psd,txt,rtf,doc,docx,xls,xlsx,xlsm,,wks,ods,ots,xlr,tsv,csv,odt,zip,bmp,7z,bz2,tar,tar.gz,pdf');
        
        
        $submit = new Zend_Form_Element_Submit('upload');
        $submit->setLabel('Upload')
        ->setOptions(array('class' => 'submit'));
        
        $this->addElement($page)
        	 ->addElement($files)
        	 ->addElement($submit);
    }
    public function getUserId() {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
            return (int) $auth->getIdentity()->userId;
        } else {
            return null;
        }
    }
}