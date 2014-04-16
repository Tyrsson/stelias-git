<?php
class Admin_Form_SkinSettings extends Zend_Form
{
    public function init()
    {
        $skins = new Admin_Model_Skins();
        $curSkin = new Zend_Form_Element_Select('skin');
        $curSkin->setLabel('Select Skin')->setMultiOptions($skins->fetchSelect()->toArray());
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Select Skin');
        
        $this->addElement($curSkin)->addElement($submit);
        
    }
}