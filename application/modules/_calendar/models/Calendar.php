<?php

/**
 * Calendar_Model_Calendar
 *  
 * @author Joey
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Calendar_Model_Calendar extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'calendar';

    protected $_primary = 'id';

    protected $_sequence = true;

    public $weekCount;

    public $dayCount;

    public $date;
    
    public $name;
    
    public $year;
    
    public $month;
    
    public $week;
    
    public $day;
    
    public $hour;
    
    public $minute;
    
    public $second;
    
    public $eventMonths = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
    );

    protected $isLeapYear = false;

    protected $timestamp;

    public function init ($name = 'default')
    {
        parent::init();
        $this->name = $name;
        
        $this->date = new Zend_Date();
        $this->timestamp = $this->date->getTimestamp();
        
        $this->year = $this->date->getYear();
        $this->month = $this->date->getMonth();
        $this->week = $this->date->getWeek();
        $this->day = $this->date->getDay();
        $this->hour = $this->date->getHour();
        $this->minute = $this->date->getMinute();
        $this->second = $this->date->getSecond();
        
        if ($this->date->isLeapYear()) {
            $this->isLeapYear = true;
            $this->dayCount = 366;
        }
        if (!$this->isLeapYear) {
            $this->dayCount = 365;
        }
        $this->weekCount = 52;
    }
    public function fetchDefault()
    {
        $query = $this->select()->from($this->_name, array('id', 'name', 'monthRangeMin', 'monthRangeMax', 'type', 'googleUserName', 'googlePassWord'))->where('name = ?', $this->name);
        return $this->fetchRow($query);
    }
    public function getEventMonths()
    {
        return $this->eventMonths;
    }

    public function fetchMonthIdByName($month) {
        $needle = ucfirst($month);
        if(!in_array($needle, $this->eventMonths)) {
            //throw new Zend_Db_Exception('Unknown month');
        }
        return array_search(ucfirst($needle), $this->eventMonths);
    }
    public function getEventDaysByMonth($year, $month)
    {
        if($month == 'October' && $year == 2012) {
        $result = array('year' => 2012, 'month' => 'October', 'days' => '2,14,16,18,31');
        return $result;
        } else {
            return null;
        }
    }
    public function fetchCalIdByName($calName) {
        $sql = $this->select()->from($this->_name, array('id', 'name'))->where('name = ?', $calName);
        $row = $this->fetchRow($sql);
        return $row->id;
    }
}
