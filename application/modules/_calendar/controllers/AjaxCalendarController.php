<?php

/**
 * Calendar_AjaxCalendarController
 * 
 * @author
 * @version 
 */

require_once 'Dxcore/Controller/Action.php';

class Calendar_AjaxCalendarController extends Dxcore_Controller_Action
{

    public $context = array(
            'calendar' => array(
                    'ajax',
                    'json',
                    'xml'
            ),
            'month' => array(
                    'ajax',
                    'json',
                    'xml'
            ),
            'week' => array(
                    'ajax',
                    'json',
                    'xml'
            ),
            'day' => array(
                    'ajax',
                    'json',
                    'xml'
            )
    );
   
    public $mCalendar;
    public $mMonth;
    public $mWeek;
    public $mDay;

    public function init ()
    {
        parent::init();
        
        $this->_helper->contextSwitch()->initContext();
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        
    }
    public function calendarAction()
    {
        
    }
    public function monthAction()
    {
        
    }
    public function weekAction()
    {
        
    }
    public function dayAction()
    {
        
    }
    public function eventAction()
    {
        
    }
}
