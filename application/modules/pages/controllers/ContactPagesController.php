<?php

class Pages_ContactPagesController extends Dxcore_Controller_Action
{
	public $moduleSettings;
	
    public function init()
    {
    	parent::init();
    }

    public function indexAction()
    {    
        $form = new Pages_Form_Contact();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
            	$mail = new Zend_Mail();
                $this->post = $form->getValues();

                $toEmail = $this->settings->sendTo;
                
                if (!$this->appSettings->showLocations) {
                	$mail->setFrom($this->settings->sendFrom, $this->host);
                } elseif ($this->settings->showLocations) {
                	$mail->setFrom($this->post['email'], $this->post['name']);
                }

                $message = $this->post['name'] . "\n" . $this->post['email'] . "\n" . $this->post['number'] . "\n" . $this->post['Editor'];

                $mail->setBodyText($message);
                
                $mail->addTo($toEmail);
                $mail->setSubject('Contact form submission');
                $mail->send();

                $this->_helper->getHelper('FlashMessenger')
                	->addMessage("Thank you. Your message was successfully sent.<br /><br /> Your page will be redirected in 2 seconds.");
                //$this->_redirect('/contact/success');
            }
        } else {
        	$formData = array(
        		'email' => $this->isLogged ? $this->user->email : '', 
      			'name'  => $this->isLogged ? $this->user->firstName . ' ' . $this->user->lastName : ''
        	);
        	$form->populate($formData);
        }
        $this->view->form = $form;
    }
    public function successAction(){}
}


