<?php

/**
 * BulletinController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';

class Filemanager_AdminController extends Dxcore_Controller_AdminAction
{

    public function init() {
        parent::init();
        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('index', array('html'));
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
//         if($this->isAjax()) { // boolean
//             $this->_helper->layout()->disableLayout();
//             switch(true) {
//             	case $this->_request->isPost() :
//             	    if($form->isValid($this->_request->getPost())) {
//             	        $data = $form->getValues();
//             	    }
//             	    break;
//             	case $this->_request->isGet() :
//             	    $count = $this->_request->getParam('fileCount', 1);
//             	    //$file->setMultiFile($count);
//             	    break;
//             }
//         }
//         else {
//             switch(true) {
//             	case $this->_request->isPost() :
//             	     if($form->isValid($this->_request->getPost())) {
//             	         $data = $form->getValues();
//             	     }
//             	    break;
//             	case $this->_request->isGet() :
//             	    $count = $this->_request->getParam('fileCount', 1);
//             	    //$file->setMultiFile($count);
//             	    break;
//             }
//         }

    }
    public function uploadAction() {
       
    }
}
