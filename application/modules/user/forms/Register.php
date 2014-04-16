<?php
class User_Form_Register extends Aurora_Form_Form
{
    public function init ()
    {
    	parent::init();
    	$this->setName('register');
        // create text input for name
        $userName = new Zend_Form_Element_Text('userName');
        $userName->setLabel('Username (used for login):')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            ->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
            
        $firstName = new Zend_Form_Element_Text('firstName');
        $firstName->setLabel('First Name:')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            ->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
            
        $lastName = new Zend_Form_Element_Text('lastName');
        $lastName->setLabel('Last Name:')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            ->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
        // create text input for email address
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email Address:');
        $email->setOptions(array('size' => '30'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true)
            ->addValidator('EmailAddress', array('allow' => Zend_Validate_Hostname::ALLOW_DNS,'mx' => true))
            ->addFilter('HTMLEntities')
            ->addFilter('StringToLower')
            ->addFilter('StringTrim');
        
        $passWord = $this->createElement('Password', 'passWord');
        $passWord->setLabel('Password:');
        $passWord->setRequired(true);
        $passWord->addValidator(new Zend_Validate_StringLength(6, 12));
        $passWord->setAttrib('class', 'txt');
        
        $confirmPassword = $this->createElement('passWord', 'confirm_password');
        $confirmPassword->setLabel('Confirm Password:');
        $confirmPassword->setRequired(true);
        $confirmPassword->addValidator(new Zend_Validate_StringLength(6, 12));
        $confirmPassword->addValidator(new Zend_Validate_Identical(Zend_Controller_Front::getInstance()->getRequest()->getParam('passWord')));
            
        
        $recaptcha = new Zend_Service_ReCaptcha($this->appSettings->recaptchaPublicKey, 
        $this->appSettings->recaptchaPrivateKey, NULL, array('theme' => 'clean'));
        $captcha = new Zend_Form_Element_Captcha('captcha', 
        array('label' => 'Type the characters you see in the picture below.', 
        	  'captcha' => 'ReCaptcha', 
        	  'captchaOptions' => array('captcha' => 'ReCaptcha', 
        	  'service' => $recaptcha)));
            
        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register');
        //$submit->setLabel('Register')->setOptions(array('class' => 'submit uniqueButtonClass'));
        // attach elements to form
           $this->addElement($userName)
                ->addElement($firstName)
                ->addElement($lastName)
                ->addElement($email)
                ->addElement($passWord)
                ->addElement($confirmPassword);
           
           switch($this->appSettings->enableCaptcha) {
               case true :
                   //$this->addElement($captcha);
                   break;
               case false :
           
                   break;
           }
           
           $this->addElement($submit);
    }
//     public function loadDefaultDecorators()
//     {
//         $this->setDecorators(array(
//             'FormElements',
//             'Fieldset',
//             'Form'
//         ));
//     }
}