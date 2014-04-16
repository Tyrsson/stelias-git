<?php
class Pages_Form_Contact extends Zend_Dojo_Form {
	
	public function init() {
		
		/* Form Elements & Other Definitions Here ... */
		
		// initialize form
	    $this->setElementsBelongTo('contact');
	    $this->setName('contact');
		//$this->setAction('/contact/index')
			//->setMethod('post');

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name')
			 //->setOptions(array('size' => '30'))
		     ->setRequired(true)
		     //->addValidator ( 'NotEmpty', true )
		     ->addFilter('HtmlEntities')
		     ->addFilter('StringTrim');
		// create text input for email address
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email Address')
			  //->setOptions(array('size' => '30'))
			  ->setRequired(true)
			  //->addValidator('NotEmpty', true)
			  ->addValidator('EmailAddress', true)
			  ->addFilter('StringToLower')
			  ->addFilter ( 'StringTrim' );
		// create text input for phone number
		
		$number = new Zend_Form_Element_Text ( 'number' );
		$number->setLabel('Phone Number (Optional)' )
		//->setOptions(array('size' => '30'))
		//->setRequired(true)
		//->addValidator('NotEmpty', true)
		->addFilter('HtmlEntities')
		->addFilter('StringToLower')
		->addFilter ( 'StringTrim' );
		
		// create text input for message body
		
		//$editor = new Zend_Form_Element_Textarea ( 'Editor' );
		$editor = new Zend_Dojo_Form_Element_Editor ( 'Editor' );
		$editor->setLabel('Message');
		$editor->setRequired(true)
		->setAttrib('COLS', '30')
		->setAttrib('ROWS', '4')
		->addFilter('HtmlEntities')
		->addFilter('StringTrim');
		// create captcha
		
		$appSettings = Admin_Model_SettingsGateway::getInstance();
		$options['ssl'] = Zend_Registry::get('serverScheme') === 'https' ? true : false;
		$recaptcha = new Zend_Service_ReCaptcha("$appSettings->recaptchaPublicKey", "$appSettings->recaptchaPrivateKey", $options, array('theme' => 'clean'));
		$captcha = new Zend_Form_Element_Captcha ( 'captcha', array (
				'label' => 'Type the characters you see in the picture below.',
				'captcha' => 'ReCaptcha',
				'captchaOptions' => array (
						'captcha' => 'ReCaptcha',
						'service' => $recaptcha 
				)	 
			)
		 );
		
		$submit = new Zend_Form_Element_Submit('submit');
		//$submit->setLabel('Submit')->setOptions(array('class' => 'submit'));
		
		$this->addElement($name)
		->addElement($email)
		->addElement($number)
		->addElement($editor);
		
		switch($appSettings->enableCaptcha) {
		    case true :
		        $this->addElement($captcha);
		        break;
		    case false :
		        
		        break;
		}
		
		$this->addElement($submit);
	}
	public function loadDefaultDecorators() 
	{
		$this->setDecorators ( array (	
				'FormElements',
				'Form' 
			)
		 );
	}
}