<?php
/**
 * PagesController
 * 
 * @author Joey Smith
 * @version 0.9
 */
require_once 'Zend/Controller/Action.php';
class Pages_UploadController extends Zend_Controller_Action
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

    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        $form = new Pages_Form_Upload();
        
        if($this->_request->isPost()) {
        
            if(isset($this->_request->albumId)) {
        
                $row = $this->albums->fetch($this->_request->albumId);
                
                if(! $this->user->user_id === $row->userId) 
                {
                    throw new Zend_Application_Exception('Access Denied');
                }
        
                $formFiles = $form->getElement('files');
                $formFiles->setDestination($row->albumLocalPath);
        
                //$file2 = $form->getElement('file2');
                //$file2->setDestination($row->albumLocalPath);
                
                if($form->isValid($this->_request->getPost())) {
        
                    $files = $form->getValues();
                    
                    Zend_Debug::dump($files);
                    
                    $uploadcount = 0;
                    
//                     if($file1->isUploaded())
//                     {
//                         $row = $this->files->fetchNew();
//                         $row->fileName = $file1->getFileName($value = null, $path = false);
//                         $row->albumId = $this->_request->albumId;
//                         $row->createdOn = $this->date;
//                         $row->userId = null !== $this->user->user_id ? $this->user->user_id : 0;
//                         $row->updated = '';
//                         $row->public = 1;
//                         $id = (int) $row->save();
//                         if($id > 0)
//                         	$uploadcount++;
                        
//                     } else {
//                     	$this->messenger->addMessage('File 1 was not uploaded!');
//                     }
//                     if($file2->isUploaded())
//                     {
//                         $row = $this->files->fetchNew();
//                         $row->fileName = $file2->getFileName($value = null, $path = false);
//                         $row->albumId = $this->_request->albumId;
//                         $row->createdOn = $this->date;
//                         $row->userId = null !== $this->user->user_id ? $this->user->user_id : 0;
//                         $row->updated = '';
//                         $row->public = 1;
//                         $id = (int) $row->save();
//                         if($id > 0)
//                         	$uploadcount++;
                         
//                     } else {
//                     	$this->messenger->addMessage('File 2 was not uploaded!');
//                     }
//                     if($uploadcount === 1) {
//                     	$this->messenger->addMessage('One file successfully uploaded, one file failed upload');
//                     } 
//                     elseif($uploadcount === 2) {
//                     	$this->messenger->addMessage('All files successfully uploaded');
//                     } else {
//                     	$this->messenger->addMessage('All Files failed to upload');
//                     }   
                }
            }        
        }
        $this->view->form = $form;
    }    
    public function delete($user_id, $email) {
        if ($user_id && $email) {
            $file_name = $this->_request->getParam('files');
            // this has been customized to remove only specific images in certain user_id folders
            // you should modify that to your needs
            $file_path = PUBLIC_PATH. $this->uploads_rel. $user_id. '/'. $file_name;
            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        }
        echo json_encode($success);
    }
    
}
