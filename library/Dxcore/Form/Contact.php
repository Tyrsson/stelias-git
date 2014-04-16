<?php
class Dxcore_Form_Contact extends Zend_Dojo_Form {

    public function init() {
                
        /* Form Elements & Other Definitions Here ... */
        // initialize form
        $this->setAction('/contact/index')
                ->setMethod('post');
// create text input for name
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('Alpha', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');
// create text input for email address
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address:');
        $email->setOptions(array('size' => '50'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('EmailAddress', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringToLower')
                ->addFilter('StringTrim');
// create text input for message body
        $editor = new Zend_Form_Element_Textarea('Editor');
        $editor->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');
// create captcha
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
                    'captcha' => array(
                        'captcha' => 'Image',
                        'wordLen' => 6,
                        'timeout' => 300,
                        'width' => 300,
                        'height' => 100,
                        'imgUrl' => '/captcha',
                        'imgDir' => APPLICATION_PATH . '/../public/captcha',
                        'font' => APPLICATION_PATH .
                        '/../public/fonts/LiberationSansRegular.ttf',
                    )
                ));
        $captcha->setLabel('Verification code:');
// create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Send Message')
                ->setOptions(array('class' => 'submit'));
// attach elements to form
        $this->addElement($name)
                ->addElement($email)
                ->addElement($editor)
                ->addElement($captcha)
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

