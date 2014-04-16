<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class User_Form_EditUser extends Zend_Dojo_Form
{
    public function init ()
    {

        $ident = Zend_Auth::getInstance()->getIdentity();
        //$this->setAction('/user/edit')->setMethod('post');

        $userid = new Zend_Form_Element_Hidden('userId');

        $role = new Zend_Form_Element_Select('role');
        $role->setLabel('User Role')
        	 ->setMultiOptions(array('admin' => 'Administrator', 'jradmin' => 'Jr Administrator', 'moderator' => 'Moderator', 'user' => 'Normal User'));
        
               
        $username = new Zend_Form_Element_Text('userName');
        $username->setLabel('User Name (Used for login):')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            ->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
        
        $firstname = new Zend_Form_Element_Text('firstName');
        $firstname->setLabel('First Name:')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            //->addValidator('Alnum')
            //->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
        $lastname = new Zend_Form_Element_Text('lastName');
        $lastname->setLabel('Last Name:')
            ->setOptions(array('size' => '30'))
            ->setRequired(true)
            //->addValidator('Alnum')
            //->addFilter('HtmlEntities')
            ->addFilter('StringTrim');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email Address:');
        $email->setOptions(array('size' => '50'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true)
            ->addValidator('EmailAddress', true)
            //->addFilter('HTMLEntities')
            ->addFilter('StringToLower')
            ->addFilter('StringTrim');
            
        $city = new Zend_Form_Element_Text('city');
        $city->setLabel('City:')
            ->setOptions(array('size' => '20'))
            ->setRequired(true)
            ->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
            
        $zipcode = new Zend_Form_Element_Text('zipcode');
        $zipcode->setLabel('Zip Code:')
            ->setOptions(array('size' => '10'))
            ->setRequired(true)
            //->addValidator('Alnum')
            ->addFilter('HtmlEntities')
            ->addFilter('StringTrim');
            
        $state = new Zend_Form_Element_Select('state');
        $state->setLabel('State:')->setMultiOptions(
        array('1' => 'Alabama', '2' => 'Alaska', '3' => 'Arizona'));
        
        $status = new Zend_Form_Element_Select('uStatus');
        $status->setLabel('Status:')->setMultiOptions(array('disabled' => 'disabled', 'enabled' => 'enabled'));
            
        $shipAddOne = new Zend_Form_Element_Text('shipaddone');
        $shipAddOne->setLabel('Shipping Address One:');
        $shipAddOne->setOptions(array('size' => '100'))
            	   ->setRequired(true)
            	   ->addValidator('NotEmpty', true)
                   ->addFilter('HtmlEntities')
                   ->addFilter('StringTrim');
                   
        $shipAddTwo = new Zend_Form_Element_Text('shipaddtwo');
        $shipAddTwo->setLabel('Shipping Address Two:');
        $shipAddTwo->setOptions(array('size' => '100'))
            	   //->setRequired(true)
            	   //->addValidator('NotEmpty', true)
                   ->addFilter('HtmlEntities')
                   ->addFilter('StringTrim');
        /*$password = $this->createElement('password', 'password');
        $password->setLabel('Password:');
        $password->setRequired(true);
        $password->addValidator(new Zend_Validate_StringLength(6, 12));
        $password->setAttrib('class', 'txt');
        $confirmPassword = $this->createElement('password', 'confirm_password');
        $confirmPassword->setLabel('Confirm Password:');
        $confirmPassword->setRequired(true);
        $confirmPassword->addValidator(new Zend_Validate_StringLength(6, 12));
        $confirmPassword->addValidator(
        new Zend_Validate_Identical(
        Zend_Controller_Front::getInstance()->getRequest()
            ->getParam('password')));*/
        // create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit')->setOptions(array('class' => 'submit'));
        // attach elements to form
        //$this->addElement($groupid)
        $this->addElement($userid)
             ->addElement($role)
             //->addElement($company)
            // ->addElement($hiddengroupid)
             ->addElement($status)
             ->addElement($username)
            ->addElement($firstname)
            ->addElement($lastname)
            ->addElement($email)
            //->addElement($city)
            //->addElement($zipcode)
            //->addElement($state)
            //->addElement($shipAddOne)
            //->addElement($shipAddTwo)
           // ->addElement($password)
           // ->addElement($confirmPassword)
            ->addElement($submit);  
    }
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form'
        ));
    }
}