<?php
class Festival_Form_CreateMenu extends ZendX_JQuery_Form
{
    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Menu Name');
        
        $date = new ZendX_JQuery_Form_Element_DatePicker('date', array('jQueryParams' => array('dateFormat' => 'mm/dd/yy')));
        $date->setLabel('Date');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        
        $this->addElements(array($date, $name, $submit));
    }
}