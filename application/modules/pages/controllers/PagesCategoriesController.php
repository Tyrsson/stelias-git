<?php
/**
 * PagesController
 * 
 * @author Joey Smith
 * @version 0.9
 */
require_once 'Zend/Controller/Action.php';
class Pages_PagesCatergoriesController extends Zend_Controller_Action
{
    public $albums;
    public $files;
    public $auth;
    public $acl;
    public $user;
    public $isLogged = false;
    public $messenger;
    public $tags;
    
    public function init() {
        
        $this->auth = Zend_Auth::getInstance();
        $this->acl = new Dxcore_Acl($this->auth);
        
        $this->albums = new Pages_Model_Albums();
        $this->files = new Pages_Model_Files();
        $this->tags  = new Pages_Model_Tags();
        
        if($this->auth->hasIdentity()) {
            $this->user = $this->auth->getIdentity();
            //Zend_Debug::dump($this->user);
            $this->isLogged = true;
        }
        
        $this->messenger = $this->_helper->getHelper('FlashMessenger');
        
        // Get the message stack for flashmessenger
        if ($this->messenger->getMessages())
        {
            $this->view->messages = $this->messenger->getMessages();
        }
        
//         $file_id =1;
//         $fileTable = new Pages_Model_Files();
//         $file = $fileTable->find($file_id)->current();
//         $tagArr = $file->getTags()->getAsArray();
        
        //Zend_Debug::dump($tagArr, true);

    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated PagesController::indexAction() default action
        if($this->isLogged) {
            
        	$this->view->albums = $this->albums->fetchAlbumList($this->user->user_id);
        	
        } else {
            $this->messenger->addMessage('You have not saved any albums');
        }
    }
    public function galleryAction() {
    	
    	if(isset($this->_request->albumId)) {
    		
    		$this->view->tags = $this->tags;
    		
    		$album = $this->albums->fetch($this->_request->albumId);
    		
    		$this->view->albumTitle = ucwords(str_replace('_', ' ', $album->albumName));
    		
    		$this->view->basePath = '/modules/media/albums/' . $this->_request->albumId;

    		$this->view->paginator = $this->files->fetchPage($this->_request->albumId, $this->_request->getParam('page', 1));
    	} else {
    		$this->messenger->addMessage('The requested album was not found');
    	}

    }
    
}
