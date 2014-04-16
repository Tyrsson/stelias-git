<?php

/**
 * AdminMediaController
 *
 * @author Joey Smith
 * @version 0.9.1
 */

require_once 'Zend/Controller/Action.php';
class Media_AdminMediaController extends Dxcore_Controller_AdminAction {

	public $albums;
	public $files;

	public function init()
	{
		parent::init(); // calls parent controller, performs acl checks etc see parent for details
		$this->albums = new Media_Model_Albums();
		$this->files = new Media_Model_Files();
	}

	public function indexAction() {

	}
	public function managealbumsAction()
	{
		/*
		 * Albums are only db records, they will not exist in the directory structure of the application
		 */
	    if(isset($this->get['step'])) {
	        $filter = new Zend_Filter_Digits();
	        $step = (int) $filter->filter($this->get['step']);
	    } else {
	        $step = 1;
	    }
	    if($step === 1)
	    {
	        //$form = new Media_Form_CreateAlbum();
	    } elseif( $step === 2) {
	        //$form = new Media_Form_UploadFiles();
	    }
	    // non of the above is being used at the moment
	    $form = new Media_Form_ManageAlbums();

		//$form = new Media_Form_CreateAlbum();
		if ($this->_request->isPost()) { // is this request $_POST ?
			//Zend_Debug::dump($this->post);
			if ($form->isValid($this->_request->getPost())) { // pass the post data to the form for filtering and validation
				//$this->post = $form->getValues(); // get the values from the form, after filters have been applied
				//$row = $this->albums->fetchNew(); // fetch a new row with all null values for saving post data

				//Zend_Debug::dump($this->post);
				//$row->albumDesc = '';
				//$row->userId = $this->user->userId;
				//$save = $row->save();
// 				if ($save > 0) { // we have a successful save so we want to add a message to the stack, send to success action, redirect to upload action
// 				    $ns = new Zend_Session_Namespace($this->module);
// 				    $ns->albumName = $this->post['albumName'];
// 				    $this->messenger->addMessage('The album description was saved successfully!');
// 					//$this->_redirect('/admin/media/createalbum?albumName='. $this->post['albumName'] . '&step=2');
// 				} else { // request is not post, show the form
// 					throw new Zend_Application_Exception('An Unknown exception occurred while trying to process your request');
// 				}
			}
		} else {

		}
		$this->view->step = $step;
		$this->view->form = $form; // assign the form to the view for rendering
	}
	public function uploadfilesAction()
	{
		$ns = new Zend_Session_Namespace($this->module);
		$ns->albumName = $this->_request->albumName;
		//Zend_Debug::dump($ns->{$this->module}->albumName);
		//Zend_Debug::dump($this->files->fetchToAddDesc('Group.png', $albumName = 'testing'));
	}
}
