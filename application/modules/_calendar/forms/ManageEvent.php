<?php

class Calendar_Form_ManageEvent extends Zend_Form
{

    public function init ()
    {
        $eventId = new Zend_Form_Element_Hidden('eventId');
        $flag = new Zend_Form_Element_Hidden('flag');
        $year = new Zend_Form_Element_Hidden('year');
        $month = new Zend_Form_Element_Hidden('month');
        $day = new Zend_Form_Element_Hidden('day');
        $calName = new Zend_Form_Element_Hidden('calName');
        
        $name = new Zend_Form_Element_Text('eventName');
        $name->setLabel('Event Name');
        
        $linkOne = new Zend_Form_Element_Text('linkOne');
        $linkOne->setLabel('Link One');
        
        $linkTwo = new Zend_Form_Element_Text('linkTwo');
        $linkTwo->setLabel('Link Two');
        
        $eventDetails = new Zend_Form_Element_Textarea('eventContent');
        $eventDetails->setAttrib('cols', '40')
                       ->setAttrib('rows', '4');
        $eventDetails->setLabel('Event Content');
        
        $submit = new Zend_Form_Element_Submit('submit');
        
        $this->addElement($name)
            ->addElement($linkOne)
            ->addElement($linkTwo)
            ->addElement($eventDetails)
            ->addElement($submit)
            ->addElement($year)
            ->addElement($month)
            ->addElement($day)
            ->addElement($calName)
            ->addElement($eventId)
            ->addElement($flag);
    }
}