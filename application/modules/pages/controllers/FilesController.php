<?php
/**
 * PagesController
 * 
 * @author Joey Smith
 * @version 0.9
 */
require_once 'Zend/Controller/Action.php';
class Pages_FilesController extends Zend_Controller_Action
{
    public $albums;
    public $files;
    public $lookup;
    public $date;
    public $user;
    public $auth;
    public $isLogged = false;
    public $messenger;
    
    public function init() {
        
    	$this->auth = Zend_Auth::getInstance();
    	if($this->auth->hasIdentity()) {
    		$this->user = $this->auth->getIdentity();
    		$this->isLogged = true;
    	} else {
    		$this->user = new stdClass();
    		$this->user->user_id = null;
    	}
    	
    	$this->date = Zend_Registry::get('today_date');
        $this->albums = new Pages_Model_Albums();
        $this->files = new Pages_Model_Files();
        $this->lookup = new Pages_Model_Lookup();
        
        $this->messenger = $this->_helper->getHelper('FlashMessenger');
        
        // Get the message stack for flashmessenger
        if ($this->messenger->getMessages())
        {
        	$this->view->messages = $this->messenger->getMessages();
        }
        
        //$fileId =1;
        //$files = new Pages_Model_Files();
        //$file = $this->files->find($fileId)->current();
        //$tags = $file->getTags()->getAsArray();
        
        //Zend_Debug::dump($tagArr, true);

    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated PagesController::indexAction() default action
    }
    public function deleteAction() {
    	if($this->_request->isGet()) {
    		if(isset($this->_request->id)) {
    			self::delete($this->_request->id);
    		}
    	}
    }
    public function delete($id) {
    	$row = $this->files->fetch($id);
    	$row->delete();
    }
    
}
