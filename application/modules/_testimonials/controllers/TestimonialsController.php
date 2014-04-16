<?php

/**
 * TestimonialsController
 *
 * @author Joey Smith
 * @version
 */
class Testimonials_TestimonialsController extends Dxcore_Controller_Action
{
	public $guestBook;
	
	public function init()
	{
		parent::init();

		$this->testimonials = new Testimonials_Model_Testimonials();
		//$this->url = $this->_request->getRequestUri();
		$this->url = $this->requestUri;
		
	}
	public function preDispatch()
	{
	    // This allows access only module is enabled
	    switch($this->appSettings->testimonialsIsActive)
	    {
	        case true :
	            
	            break;
	        case false :
	            if(!$this->acl->isAllowed($this->user->role, $this->module,  'dxadmin.manage.all'))
	            {
	                throw new Zend_Controller_Action_Exception('Module is not active', 404);
	            }
	            break;
	    }
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		parent::init();
		$page = $this->_request->page;
		$this->view->pageNumber = $page;
		$this->view->user = $this->user;
		//$this->view->acl = new Testimonials_Acl_Testimonials();
		$this->view->paginator = $this->testimonials->getOnePage($page);

		/*if ($this->isLogged) {
			$this->view->allowTestimonials = $this->user->allowTestimonials;
		} else {
			$this->view->allowTestimonials = 'No';
		}*/
		$this->view->acl = $this->acl;
	}

	public function submitAction()
	{
		$date = new Zend_Date();
		$timestamp = $date->get(Zend_Date::TIMESTAMP);

		$form = new Testimonials_Form_Submit();
		$entry = $this->testimonials->fetchNew();

		if ($this->_request->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$formData = $form->getValues();
				$entry->guestName   = $formData['guestName'];
				$entry->rating      = $formData['rating'];
				$entry->content     = $formData['content'];
				$entry->createdDate = $timestamp;
				$id = (int) $entry->save();
				if ($id > 0) {
					$this->messenger->addMessage('Success, your submission will be reviewed and approaved shortly!');
					$this->_redirect('/testimonials/success');
				} else {
					$this->messenger->addMessage('There was a problem submitting your testimonial!');
				}
			}
		} else {
			$form->populate(
				$values = array(
					'guestName' => $this->isLogged ? $this->user->firstName . ' ' . $this->user->lastName : ''
				)
			);
		}

		if($this->appSettings->allowUserPostNew && $this->acl->isAllowed($this->user->role, $this->module,  'testimonials.post.new')
				||$this->acl->isAllowed($this->user->role, $this->module,  'testimonials.manage') ) {
			$this->view->form = $form;
		}
		
	}

	public function displayAction()
	{
		if (isset($this->_request->id)) {
		    $this->view->entry = $this->testimonials->fetch($this->_request->id);
		} else {
			// $this->messenger->addMessage('No testimonial to display');
		}

	}

	public function successAction() {}
}
