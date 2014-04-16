<?php
class Admin_Form_Install extends Dxcore_Form_Base
{
    public function init() {
        parent::init();
        
        $go = new Zend_Form_Element_Checkbox('proceed');
        $go->setLabel('Install Dxcore Base');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Install')
                ->setOptions(array('class' => 'submit')); 
        
        $this->addElement($go)
        		->addElement($submit);
    }
}