<?php

/**
 * Calendar_AdminCalendarController
 * 
 * @author
 * @version 
 */

require_once 'Dxcore/Controller/AdminAction.php';

class Calendar_AdminController extends Dxcore_Controller_AdminAction
{
    public $mCalendar;
    public $mMonth;
    public $mWeek;
    public $mDay;
    public $mEvents;
    
    public function init()
    {
        parent::init();
        $ajaxHelper = $this->getHelper('AjaxContext');
        $ajaxHelper->addActionContext('manage-event', 'html')->initContext();
        $this->mCalendar = new Calendar_Model_Calendar();
        $this->mEvents = new Calendar_Model_CalendarEvents();
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AdminCalendarController::indexAction() default
    // action
    }
    public function manageCalendarAction()
    {
        //$cal = new Calendar_Service_Calendar();
        
        if($this->_request->isGet() && isset($this->_request->selectMonth)) {
            $cal = new Calendar_Service_Calendar($this->_request->selectMonth);
        }
        else {
            //today's date
            $date = new Zend_Date();
        
            $cal = new Calendar_Service_Calendar($date->toString('MMMM yyyy'));
        }
        //allow only months from -1 to 12 months ahead
        
        $cal->setMonthsInRange(-1, 12);
        $this->view->calUi = $cal->getCalendarHtml(
                array(
                        'showToday' => true,
                        'showPrevMonthLink' => false,
                        'showNextMonthLink' => false,
                        'tableClass' => "calendar",
                        'selectBox' => true,
                        'selectBoxName' => "selectMonth",
                        'selectBoxFormName' => "selectMonthForm"
                ));
        // here we have an edit
        if($this->_request->isPost()) {
            Zend_Debug::dump($this->_request->getPost());
            
            // here we branch based on the control flag set in the ajax action for either creation of an event or editing an event
            switch ($this->_request->flag) {
                
                case 'edit':
                    $eventData = (object) $this->_request->getPost();
                    $event = $this->mEvents->fetchById($eventData->eventId);
                    $event->setFromArray((array)$eventData);
                    $event->save();
                    break;
                    
                case 'create':
                    $event = $this->mEvents->fetchNew();
                    $eventData = $this->_request->getPost();
                    //Zend_Debug::dump($this->mCalendar->fetchMonthIdByName($eventData['month']));
                    $eventData['month'] = $this->mCalendar->fetchMonthIdByName($eventData['month']);
                    $eventData['calendarId'] = $this->mCalendar->fetchCalIdByName($eventData['calName']);
                    $event->setFromArray($eventData);
                    $event->save();
                    
                    break;
                    
                default:
                        
                break;
            }
        } 
    }
    public function manageEventAction()
    {
        $form = new Calendar_Form_ManageEvent();
        //$event = (object) $this->_request->getParams();
        //$result = $this->mEvents->fetchEvent($event);
        //Zend_Debug::dump($result);
        if($this->_request->isXmlHttpRequest())
        {
            $event = (object) $this->_request->getParams();
            $result = $this->mEvents->fetchEvent($event);
            //Zend_Debug::dump($result);
            if($result) {
                $form->flag->setValue('edit');
                $form->populate($result->toArray());
            } else {
                $form->flag->setValue('create');
                $form->populate($this->_request->getParams());
            }
        }

        $this->view->form = $form;
    }
    public function editCalendarAction()
    {
        
    }
    public function createEventAction()
    {
        
    }
    public function editEventAction()
    {
        
    }
}