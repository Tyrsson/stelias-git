<?php
class Festival_Form_ChangeSort extends Zend_Form
{
    public function init()
    {
        $this->setMethod('get');
        $sort = new Zend_Form_Element_Select('type');
        $sort->setLabel('Change Sort');
        $sort->setMultiOptions(array('pickup' => 'Pick Up', 'delivery' => 'Delivery'));
        $sort->setAttrib('onchange', 'submit()');

        $this->addElement($sort);
    }
}