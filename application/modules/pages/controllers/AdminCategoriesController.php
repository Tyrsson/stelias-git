<?php
/**
 * AdminPagesController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class Pages_AdminCategoriesController extends Dxcore_Controller_Action
{
    /**
     * 
     * @var object Pages_Model_Albums
     */
    public $albums; 

    public function init() {
                
        
        $this->albums = new Pages_Model_Categories();
        
        $this->messenger = $this->_helper->getHelper('FlashMessenger');
        
        // Get the message stack for flashmessenger
        if ($this->messenger->getMessages())
        {
            $this->view->messages = $this->messenger->getMessages();
        }
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AdminPagesController::indexAction() default action
        
        
    }
    public function createAction() {
        
        $form = new Pages_Form_CreateAlbum();
        
        if($this->_request->isPost()) {
            
            if($form->isValid($this->_request->getPost())) {
                
                $post = $form->getValues();
                $row = $this->albums->fetchNew();
                $row->albumName = strtolower(str_replace(' ', '_', $post['albumName']));
                $row->userId = $this->user->user_id;
                $row->public = 1;
                $row->createdOn = Zend_Registry::get('today_date');
                $id = (int) $row->save();
                if(is_int($id) && $id > 0) {
                    
                    if($row->createAlbum())
                    {
                    	$this->messenger->addMessage('Your Pages Album was created successfully');
                    	$this->_redirect('/admin/media/albums/success');
                    }
                }
            }
        } 
        $this->view->form = $form;
    }
    public function deleteAction() {
        
        $form = new Pages_Form_DeleteAlbum();
        
        if($this->_request->isPost()) {
            if(isset($this->_request->id)) {
                self::delete($this->_request->id);
            }
        }
    	$this->view->form = $form;
    }
    public function delete($id) {
    	$row = $this->files->fetch($id);
    	$row->delete();
    }
    public function successAction()
    {
        // this is here to load the view file
    }
}
