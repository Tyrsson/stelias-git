<?php
class Dxcore_Form_SubmitPage extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
        // initialize form
        //$this->setAction('/content/submit')
                //->setMethod('post');
        
        $auth = Zend_Auth::getInstance();
        $acl = new Dxcore_Acl(Zend_Auth::getInstance());

        if($acl->isAllowed(new Dxcore_Acl_Role_User, new Dxcore_Acl_Resource_Content, 'submit')) 
        {
            
        //Drop down for page status
        $page_status = new Zend_Form_Element_Select('status');
        $page_status->setLabel('Page Status')->setMultiOptions(
        array('0' => 'Hidden', '1' => 'Published'));
        $page_name = new Zend_Form_Element_Text('page_name');
        // create text input for name
        $page_name->setLabel('Page Name:')
                ->setOptions(array('size' => '30'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                ->addValidator('Alpha', 'allowWhiteSpace', true)
                ->addFilter('HTMLEntities')
                ->addFilter('StringTrim');
        
// create text input for message body
        $page_content = new Zend_Form_Element_Textarea('page_content');
        $page_content->setLabel('Page Content:')
                //->setOptions(array('width' => '150px', 'height' => '100px'))
                ->setRequired(true)
                ->addValidator('NotEmpty', true)
                //->addFilter('HTMLEntities')
                ->addFilter('StringTrim');
// create captcha
/*        $captcha = new Zend_Form_Element_Captcha('captcha', array(
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
        $captcha->setLabel('Verification code:');*/
// create submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Create Page')
                ->setOptions(array('class' => 'submit'));
// attach elements to form
           $this->addElement($page_status)
                ->addElement($page_name)
                ->addElement($page_content)
               // ->addElement($captcha)
                ->addElement($submit);
                
        }

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
?>
