<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Dxcore_Form_LoginBar extends Zend_Dojo_Form {

    public function init() {

// initialize form
        $this->setAction('/login/index')
                ->setMethod('post');
// create text input for name
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:')
                //->setOptions(array('size' => '30'))
                ->setRequired(true)
                ->addValidator('Alnum')
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
                // create text input for email address
       /* $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address:');
        $email->setOptions(array('size' => '50'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('EmailAddress', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringToLower')
                ->addFilter('StringTrim');*/
// create text input for password
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:')
                //->setOptions(array('size' => '30'))
                ->setRequired(true)
                ->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
// create submit button
        $submit = new Zend_Form_Element_Submit('submitlogin');
        $submit->setLabel('Log In')
                ->setOptions(array('class' => 'submitlogin'));
// attach elements to form
        $this->addElement($username)
                ->addElement($password)
                ->addElement($submit);
    }
//    public function loadDefaultDecorators()
//    {
//        $this->setDecorators(array(
//            'FormElements',
//            'Fieldset',
//            'Form'
//        ));
//    }

}