<?php

/**
 * AdminCategoriesController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Admin_CategoriesController extends Dxcore_Controller_AdminAction
{
    public $cats;
    
    public function init()
    {
        parent::init();
        $this->cats = new Aurora_Model_Categories();
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        
    }
    public function createAction()
    {
        try {
            
            $form = new Aurora_Form_CreateCategory();
            //Zend_Debug::dump($form);
            switch ($this->_request->isPost()) {
                case true :
                    if($form->isValid($this->_request->getPost())) {
                        $row = $this->cats->fetchNew();
                        $row->setFromArray($form->getValues());
                        $result = $row->save();
                        if($result > 0) {
                            $this->redirect('/admin/success');
                        }
                        else {
                            throw new Zend_Application_Exception('Category could not be created.');
                        }
                    }
                    
                    break; 
                case false :
                    
                    break;
            }
            $this->view->form = $form;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
