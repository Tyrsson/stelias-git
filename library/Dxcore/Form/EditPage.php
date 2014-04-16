<?php
class Dxcore_Form_EditPage extends Dxcore_Form_SubmitPage
{
    public function init ()
    {

        parent::init();

        $this->removeElement('captcha');
        $this->removeDisplayGroup('verification');
        $this->setAction('/content/edit')->setMethod('post');

        $this->removeElement('submit');
        
        $page_id = new Zend_Form_Element_Hidden('page_id');

        $update = new Zend_Form_Element_Submit('update');
        $update->setLabel('Update Page')->setOptions(array('class' => 'update'));

        $this->addElement($page_id);
        $this->addElement($update);
    }

}
