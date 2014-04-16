<?php

class Testimonials_Form_Submit extends Aurora_Form_Form
{
	public function init()
	{
		parent::init();
		$name = new Zend_Form_Element_Text('guestName');
		$name->setLabel('Guest Name:')
			->setOptions(array('size' => '40'))
			->setRequired(true)
			->addValidator('NotEmpty', true)
			->addValidator('Alpha', true, array('allowWhiteSpace' => true))
			->addFilter('HTMLEntities')
			->addFilter('StringTrim');
		
		$rating = new Zend_Form_Element_Select('rating');
		$rating->setLabel('Rate your visit')->setMultiOptions(
			array('1' => 'One Star', '2' => 'Two Stars', '3' => 'Three Stars', '4' => 'Four Stars')
		);

		// create text input for message body
		$editor = new Zend_Form_Element_Textarea('content');
		$editor->setRequired(true)
			->setLabel('Leave a testimonial:')
			->setAttrib('COLS', '40')
			->setAttrib('ROWS', '4')
			->addValidator('NotEmpty', true)
			//->addFilter('HTMLEntities')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setOptions(array('id' => 'editor'));

		$recaptcha = new Zend_Service_ReCaptcha($this->appSettings->recaptchaPublicKey, 
        $this->appSettings->recaptchaPrivateKey, NULL, array('theme' => 'clean'));
        $captcha = new Zend_Form_Element_Captcha('captcha', 
        array('label' => 'Type the characters you see in the picture below.', 
        	  'captcha' => 'ReCaptcha', 
        	  'captchaOptions' => array('captcha' => 'ReCaptcha', 
        	  'service' => $recaptcha)));
	

		// create submit button
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit')->setOptions(array('class' => 'submit'));
	
		// attach elements to form
		$this->addElement($name)
			// ->addElement($email)
			//->addElement($rating)
			->addElement($editor);
			
		switch($this->appSettings->enableCaptcha) {
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
		$this->setDecorators(array('FormElements', 'Form'));
	}
}

?>
