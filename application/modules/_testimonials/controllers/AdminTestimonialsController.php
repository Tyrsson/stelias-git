<?php

/**
 * TestimonialsController
 * 
 * @author Joey Smith
 * @version 
 */
require_once('Dxcore/Controller/Action.php');
require_once('Zend/Date.php');

class Testimonials_AdminTestimonialsController extends Dxcore_Controller_AdminAction
{
	public $id;
	public $testimonials;
	public $url;
	
	public function init()
	{
		parent::init();
		$this->testimonials = new Testimonials_Model_Testimonials();
		$this->view->headTitle(ucfirst($this->_request->controller), 'PREPEND');
		$this->url = $this->_request->getRequestUri();
	}

	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$page = $this->_request->page;
		$this->view->pageNumber = $page;
		//$this->view->user = $this->user;
		//$this->view->acl = new Testimonials_Acl_Testimonials();
		$this->view->paginator = $this->testimonials->getOnePage($page, false);
	}

	public function deleteAction()
	{
		$id = $this->_request->getParam('id');
		$page = $this->_request->getParam('page');
		if (isset($id)) {
			$row = $this->testimonials->fetch($id);
			$row->delete();
			$this->_redirect('/admin/testimonials/' . $page);
		}
	}

	public function editAction()
	{
		$date = new Zend_Date();
		$timestamp = $date->get(Zend_Date::TIMESTAMP);
		
		$form = new Testimonials_Form_Edit();
		$params = $this->_request->getParams();
		$entry = $this->testimonials->getById($params['id']);
		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formData = $form->getValues();
				$entry->guestName   = $formData['guestName'];
				$entry->isApproved  = $formData['isApproved'];
				$entry->rating      = $formData['rating'];
				$entry->content     = $formData['content'];
				$entry->updatedDate = $timestamp;
				$entry->save();
			}
		} else {
			$form->populate($entry->toArray());
		}

		$this->view->form = $form;
	}

	public function approveAction()
	{
		$id = $this->_request->getParam('id');
		$page = $this->_request->getParam('page');
		if (isset($id)) {
			$entry = $this->testimonials->getById($id);
			$entry->isApproved = 1;
			$entry->save();
			$this->_redirect('/admin/testimonials/' . $page);
		}
	}
}