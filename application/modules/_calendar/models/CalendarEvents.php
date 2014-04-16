<?php

/**
 * Event
 *  
 * @author Joey
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Calendar_Model_CalendarEvents extends Zend_Db_Table_Abstract
{
    //const DAY_FORMAT = 'dd';
    protected $_name = 'calendarevents';
    protected $_primary = 'eventId';
    protected $_sequence = true;
    
    public $year;
    public $month;
    public $day;
    public $hour;
    
    
    public function init() {
        $this->calendar = new Calendar_Model_Calendar();
    }
    
    public function fetchEvent($event) {
        $sql = $this->select()->from($this->_name, array('eventId', 'calendarId', 'year', 'month', 'day', 'eventName', 'linkOne', 'linkTwo', 'eventContent'))
        //->where('calendarId = ?', $event->calendarId)
        ->where('year = ?', $event->year)
        ->where('month = ?', $this->calendar->fetchMonthIdByName($event->month))
        ->where('day = ?', $event->day)
        //->where('name = ?', $event->name)
        ;
        return $this->fetchRow($sql);
    }
    public function fetchById($eventId) {
        $sql = $this->select()->from($this->_name, array('eventId', 'calendarId', 'year', 'month', 'day', 'eventName', 'linkOne', 'linkTwo', 'eventContent'))
        ->where('eventId = ?', $eventId);
        return $this->fetchRow($sql);
    }
}
