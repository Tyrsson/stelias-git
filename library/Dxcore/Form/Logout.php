<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of logout
 *
 * @author Laptop
 */
class Dxcore_Form_Logout extends Zend_Form {
    //put your code here
        public function init() {
// initialize form
        $this->setAction('/login/logout')
                ->setMethod('post');
// create text input for name
//        $username = new Zend_Form_Element_Text('username');
//        $username->setLabel('Username:')
//                ->setOptions(array('size' => '30'))
//                ->setRequired(true)
//                ->addValidator('Alnum')
//                ->addFilter('HtmlEntities')
//                ->addFilter('StringTrim');
//// create text input for password
//        $password = new Zend_Form_Element_Password('password');
//        $password->setLabel('Password:')
//                ->setOptions(array('size' => '30'))
//                ->setRequired(true)
//                ->addFilter('HtmlEntities')
//                ->addFilter('StringTrim');
// create submit button
        $submit = new Zend_Form_Element_Submit('submitlogout');
        $submit->setLabel('Log Out')
                ->setOptions(array('class' => 'submitlogout'));
// attach elements to form
        $this->addElement($submit);
    }
}
?>
