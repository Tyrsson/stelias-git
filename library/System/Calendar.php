<?php
/**
 * Calendar (using Zend_Date)
 * version: 1.4 (Jul 31, 2010)
 * @requires Zend_Date,Zend_Locale,Zend_Cache,Zend_Registry,Zend_Exception
 *
 *
 *
 * Usage:
 *
 * 1) $cal = new Calendar();
 *
 * 2) $cal = new Calendar("April 2009", "nl_NL");
 *
 * 3) $cal->getCalendarHtml(array(
 *              'showToday'             => true,
 *              'showPrevMonthLink' => true,
 *              'showNextMonthLink' => true,
 *              'tableClass'            => "calendar",
 *              'selectBox'             => true,
 *              'selectBoxName'         => "selectMonth",
 *              'selectBoxFormName' => "selectMonthForm"));
 *
 */
require_once 'Zend/Locale.php';
require_once 'Zend/Date.php';

class System_Calendar
{
    protected $_locale;
    protected $_now;
    protected $_date;
    protected $_monthNames;
    protected $_dayNames;
    protected $_validDates;
    protected $_numMonthDays;
    protected $_nextMonth;
    protected $_prevMonth;
    protected $_firstDayOfWeek;
    protected $_numWeeks;
    protected $_log;
    public    $calendarName;
    // google
    public    $gdata;
    public    $service;
    public    $feed;
    public    $events;
    public    $eventFeed;
    public    $gCal;
    public    $gCalStartMin;
    public    $gCalStartMax;
    public    $reservedDates = array();
    /**
     *
     * @param String $date
     * @param String $locale
     */
    public function __construct($date = null, $locale = "en_US")
    {
        $this->setDate($date, $locale);

        $this->reservedDates = array(5,7,9,10);

        $this->eventMonths = array('August', 'September', 'October');

        $this->eventMonths['August']['eventdays'] = array(5,7,9,10);

//         if($calType === 'google')
//         {
//         	// get the needed dates as strings
//         	$this->gCalStartMin = $this->getGoogleStartMin();
//         	//Zend_Debug::dump($this->gCalStartMin);
//         	$this->gCalStartMax = $this->getGoogleStartMax();
//         	// set the service min and max dates in the service
//         	$this->service = new Calendar_Service_Google($this->gCalStartMin, $this->gCalStartMax);

//         	//Zend_Debug::dump($this->service);

//         	$gCal = $this->service->calendarFeed;
//         	//Zend_Debug::dump($gCal);
//         	foreach($gCal as $cal) {
//         		// blank for the moment as we are not supporting multiple calendars yet
//         	}

//         	$this->events = $this->service->getEventFeed();
//         	if(count($this->events) > 0) {
//         		$hasEvent = true;
//         	} else {
//         		$hasEvent = false;
//         	}
//         	//Zend_Debug::dump($this->events);
//         	$numEvent = 0;
//         	foreach ( $this->events as $event ) {
//         		echo $event->title;
// 				foreach ( $event->when as $eventDate ) {
// 					$eventDay = $this->_date->set ( $eventDate->getStartTime (), null );
// 					$this->reservedDates [++ $numEvent] = $eventDay->toString ( 'd' );
// 					Zend_Debug::dump ( $this->reservedDates [$numEvent] );
// 				}
// 			}


//         }
//         if(null !== $calName) {
//         	$this->setOptions(array('name' => $calName));
//         }
        // get the log for errors
        //$this->_log = Zend_Registry::get('log');
        // this is here for any external config, config can also be set via setOptions();
        self::init();
    }
    /**
     *
     * @param String $date
     * @param String $locale
     */
    public function setDate($date = null, $locale = "en_US")
    {
    	//locale
    	if (Zend_Locale::isLocale($locale)) {
    		$this->_now = Zend_Date::now($locale); //today
    		$this->_locale = new Zend_Locale($locale);
    	} else {
    		$this->_now = Zend_Date::now("en_US"); //today, default locale
    		$this->_locale = new Zend_Locale("en_US"); //default locale
    	}
    	//date
    	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    	try {
    		$this->_date = new Zend_Date($date, "MMMM yyyy", $this->_locale);
    	} catch (Zend_Date_Exception $e) {
    		$this->_date = new Zend_Date(null, "MMMM yyyy", $this->_locale);
    	}
    	//date params
    	$this->initDateParams($this->_date);
    }
    public function init()
    {
    }
    // set class options
    public function setOptions(array $options)
    {
    	if(array_key_exists('google', $options)) {

    	}
    	if(array_key_exists('db', $options)) {

    	}
    	if(array_key_exists('name', $options)) {
    		$this->calendarName = $options['name'];
    	}
    }
    /**
     *
     * @param Zend_Date $date
     */
    protected function initDateParams(Zend_Date $date)
    {
        $this->_monthNames = Zend_Locale::getTranslationList('Month', $this->_locale); //locale month list
        //Zend_Debug::dump($this->_monthNames, 'initDateParams: $this->_monthNames');

        $this->_dayNames = Zend_Locale::getTranslationList('Day', $this->_locale); //locale day list
        //Zend_Debug::dump($this->_dayNames, 'initDateParams: $this->_dayNames');
        #######################################
        $this->setMonthsInRange(); //set locale valid dates
        //Zend_Debug::dump($this->setMonthsInRange(-1, 12), 'initDateParams: $this->setMonthsInRange()');

        $this->_numMonthDays = $date->get(Zend_Date::MONTH_DAYS); //num days in locale month
        //Zend_Debug::dump($this->_numMonthDays, 'initDateParams: $this->_numMonthDays');

        $this->setNextMonth($date); //set the next month
        //Zend_Debug::dump($this->setNextMonth($date), 'initDateParams: $this->setNextMonth($date)');

        $this->setPrevMonth($date); //set the previous month
        //Zend_Debug::dump($this->setPrevMonth($date), 'initDateParams: $this->setPrevMonth($date)');

        $this->_firstDayOfWeek = $date->get(Zend_Date::WEEKDAY_DIGIT); //first day of the curr month
        //Zend_Debug::dump($this->_firstDayOfWeek, 'initDateParams: $this->_firstDayOfWeek');

        $this->_numWeeks = ceil(($this->getFirstDayOfWeek() + $this->getNumMonthDays()) / 7); //num weeks in curr month
        //Zend_Debug::dump($this->_numWeeks, 'initDateParams: $this->_numWeeks');
    }
    /**
     *
     * @param int $startOffset
     * @param int $endOffset
     */
    public function setMonthsInRange($startOffset=-1, $endOffset=12)
    {
        $this->_validDates = array();
        $startDate = clone $this->_now;
        $startMonth = $startDate->subMonth(abs($startOffset));
        $startNum = intval($startMonth->get("M"));
        $this->_validDates[$startMonth->get("MMMM yyyy")] = $startMonth->get("MMMM yyyy");
        for ($i = $startNum; $i <= ($startNum + $endOffset); $i ++) {
            $str = $startMonth->addMonth(1)->get("MMMM yyyy");
            $this->_validDates[$str] = $str;
        }
        unset($startDate, $startMonth, $startNum);
    }
    /**
     *
     * @param Zend_Date $date
     */
    protected function setNextMonth(Zend_Date $date)
    {
        $tempDate = clone $date;
        $this->_nextMonth = $tempDate->addMonth(1);
        unset($tempDate);
    }
    /**
     *
     * @param Zend_Date $date
     */
    protected function setPrevMonth(Zend_Date $date)
    {
        $tempDate = clone $date;
        $this->_prevMonth = $tempDate->subMonth(1);
        unset($tempDate);
    }
    /**
     *
     * @param String $locale
     */
    public function setLocale($locale = null)
    {

    	try {
    		$reg = Zend_Registry::getInstance();
    		if(null === $locale) {
    			$locale = $reg->get('Zend_Registry');
    		}
    	} catch (Exception $e) {
    		$this->_log->log('Calendar Exception: ' . 'File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage(), System_Log::NOTICE);
    	}

        if (Zend_Locale::isLocale($locale)) {
            $this->_locale = new Zend_Locale($locale);
            $this->_date->setLocale($locale);
        } else { //string
            $this->_locale = new Zend_Locale("en_US"); //default locale
            $this->_date->setLocale("en_US");
        }
        //update the date params
        $this->initDateParams($this->_date);
    }
    /**
     *
     * @param Array $arr
     * @return String
     */
    public function getCalendarHeaderHtml( $arr = NULL )
    {
    	//Zend_Debug::dump($arr, 'arr: getCalendarHeaderHtml');
    	//defaults:
        $showPrevMonthLink = false;
        $showNextMonthLink = false;
        $selectBox = false;
        $selectBoxName = "selectMonth";
        $selectBoxFormName = "selectMonthForm";
        // Only use this value if the value is not already set in the class
        $this->calendarName = $calendarName = (null !== $this->calendarName) ? $this->calendarName : 'Calendar';
        //params:
        if(is_array($arr)) {
        	extract( $arr );
        }
        //prev/next link in header display
        $pLink = $nLink = "";
        $pLinkClass = "id=\"prevMonth\" style=\"visibility: visible;\"";
        $nLinkClass = "id=\"nextMonth\" style=\"visibility: visible;\"";
        if($showPrevMonthLink) {
			$t = $this->getPrevMonthAsDateString();
			if(! array_key_exists($t, $this->_validDates)) //check if the prev month in list of valid dates
		    	$pLinkClass = "id=\"prevMonth\" style=\"visibility: hidden;\"";
		            			// Original code -> $pLink = "<a $pLinkClass href=\"?$selectBoxName=" . urlencode($t) . "\">&lt;&nbsp;$t</a>\n";
		                        $pLink = "<a $pLinkClass href=\"/calendar?$selectBoxName=" . urlencode($t) . "\">&lt;&nbsp;$t</a>\n";
		}
		if($showNextMonthLink) {
			$t = $this->getNextMonthAsDateString();
			if(! array_key_exists($t, $this->_validDates)) //check if the next month in list of valid dates
		                $nLinkClass = "id=\"nextMonth\" style=\"visibility: hidden;\"";
		            			// Original code -> $nLink = "<a $nLinkClass href=\"?$selectBoxName=" . urlencode($t) . "\">$t&nbsp;&gt;</a>\n";
		                        $nLink = "<a $nLinkClass href=\"calendar?$selectBoxName=" . urlencode($t) . "\">$t&nbsp;&gt;</a>\n";
		    }
		    //month in header display
		    $headDate = $this->getDateAsString();
		    if($selectBox) {
		    	$headDate = "\n<form name=\"$selectBoxFormName\" method=\"get\">\n";
		        $headDate .= $this->getValidDatesSelectBox(array('selectedDateStr'=> $this->getDateAsString(), 'selectBoxName'=>$selectBoxName));
		        $headDate .= "</form>\n";
		    }
        	// calendar is set when instantion
            return "<div id=\"calendar_header\"><h4 class=\"calendar-name\">$this->calendarName</h4>$pLink&nbsp;$headDate&nbsp;$nLink</div>\n";
    }
    /**
     * @return String
     */
    public function getCalendarBodyHtml( $arr = NULL )
    {
    	//defaults:
        $showToday=false;
        $tableClass="calendar";
        //params:
        if(is_array($arr)) {
        	extract( $arr );
        }
		$html = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$tableClass\">\n";
        $html .= "<tr class=\"weekdays\">\n";
        //days of the week display
        foreach($this->_dayNames as $dayShort => $dayFull)
        {
        	$html .= "<td>$dayFull</td>\n";
        }
        $html .= "</tr>\n";
        //day numbers display
        $today = $this->_now->get("d");
        $nowDate = $this->_now->get("MMMM yyyy");
        //Zend_Debug::dump($today);
        $focusDate = $this->getDateAsString();
        //Zend_Debug::dump($focusDate);
        $calDayNum = 1;

        //Jsmith
        $month = explode(" ",$nowDate);
        //Zend_Debug::dump($month[0]);
        //this hits the current month, this should be the array key that contains an array of event days for the iteration below
        //Jsmith


        //day numbers display loop
        for ($i = 0; $i < $this->getNumWeeks(); $i ++)
        {
        	$html .= "<tr class=\"days\">";
	        for ($j = 0; $j < 7; $j ++)
	        {
	        	$cellNum = ($i * 7 + $j);
	            $class = "";
	            if ($showToday && $nowDate == $focusDate && $today == $calDayNum && $cellNum >= $this->getFirstDayOfWeek())
			    {
			    	$class = "class = \"today\"";
			    }
			    if(count($this->reservedDates) > 0)
			    {
			    	if(in_array($calDayNum, $this->reservedDates))
			        {
			        	$class = "class = \"event\"";
			        }
			    }
			    if(count($this->reservedDates) > 0)
			    {
			    	if($showToday && $nowDate == $focusDate && $today == $calDayNum && $cellNum >= $this->getFirstDayOfWeek() && in_array($today, $this->reservedDates))
			    	{
			    		$class = "class = \"todayisevent\"";
			    	}
			    }
			    	$html .= "<td $class>";
			    if ($cellNum >= $this->getFirstDayOfWeek() && $cellNum < ($this->getNumMonthDays() + $this->getFirstDayOfWeek()))
			    {
			    	$html .=  Zend_Locale_Format::toNumber($calDayNum, array('locale' => $this->_locale));
			        $calDayNum ++;
			    }
			    $html .= "</td>\n";
	        }//<- second forloop
            $html .= "</tr>\n";
        }//<- first forloop
        $html .= "</table>\n";
        return $html;
    }
    /**
     * @return String
     */
    public function getCalendarHtml( $arr = NULL )
    {
        //defaults:
        $showToday = false;
        $showPrevMonthLink = false;
        $showNextMonthLink = false;
        $tableClass = "calendar";
        $selectBox = false;
        $selectBoxName = "selectMonth";
        $selectBoxFormName = "selectMonthForm";

        //params:
        if(is_array($arr))
        {
        	extract( $arr );
        }
        $html = "<div id=\"calendar_wrapper\">\n";
        $html .= $this->getCalendarHeaderHtml(array(
                        'showPrevMonthLink'    =>    $showPrevMonthLink,
                        'showNextMonthLink'    =>    $showNextMonthLink,
                        'selectBox'            =>    $selectBox,
                        'selectBoxName'        =>    $selectBoxName,
                        'selectBoxFormName'    =>    $selectBoxFormName)); //returns a div
        $html .= "<div id=\"calendar_body\">\n";
        $html .= $this->getCalendarBodyHtml(array('showToday'=> $showToday, 'tableClass'=> $tableClass)); //returns a table
        $html .= "</div>\n</div>\n";
        return $html;
    }
    /**
     * @return String
     */
    public function getValidDatesSelectBox( $arr = NULL )
    {
    	//defaults:
        $selectedDateStr = false;
        $selectBoxName="";
        //params:
        if(is_array($arr))
        {
            extract( $arr );
        }
        $html = "<select id=\"selectmonth\" name=\"$selectBoxName\" onchange=\"submit();\">\n";
        foreach($this->_validDates as $option => $value)
        {
        	$sel = "";
            if($selectedDateStr && $selectedDateStr === $option)
            {
            	$sel = "selected";
            }
            	$html .= "<option value=\"$option\" $sel>$value</option>\n";
        }
        $html .= "</select>\n";
        return $html;
    }
    /**
     * @return Array
     */
    public function getValidDates()
    {
        return $this->_validDates;
    }
    /**
     * @return Array
     */
    public function getMonthNames()
    {
        return $this->_monthNames;
    }
    /**
     * @return Array
     */
    public function getDayNames()
    {
        return $this->_dayNames;
    }
    /**
     * @return Zend_Locale
     */
    public function getLocale()
    {
        return $this->_locale;
    }
    /**
     * @return String
     */
    public function getLocaleAsString()
    {
        return $this->_locale->toString();
    }
    /**
     * @return int
     */
    public function getFirstDayOfWeek()
    {
        return $this->_firstDayOfWeek;
    }
    /**
     * @return String
     */
    public function getDateAsString()
    {
        return $this->_date->get("MMMM yyyy");
    }
    public function getGoogleStartMin()
    {
    	return $this->_date->get("yyyy-MM-dd");
    }
    public function getGoogleStartMax()
    {
    	return  $this->getYear(). '-' . $this->getMonthNum() . '-' . $this->getNumMonthDays();
    }

    /**
     * @return Zend_Date
     */
    public function getDate()
    {
        return $this->_date;
    }
    /**
     * @return int
     */
    public function getNumMonthDays()
    {
        return $this->_numMonthDays;
    }
    /**
     * @return String
     */
    public function getMonthName()
    {
        return $this->_date->get("MMMM");
    }
    /**
     * @return String
     */
    public function getMonthShortName()
    {
        return $this->_date->get("MMM");
    }
    /**
     * @return int
     */
    public function getMonthNum()
    {
        return $this->_date->get("MM");
    }
    /**
     * @return int
     */
    public function getYear()
    {
        return $this->_date->get("yyyy");
    }
    /**
     * @return String
     */
    public function getNextMonthName()
    {
        return $this->_nextMonth->get("MMMM");
    }
    /**
     * @return int
     */
    public function getNextMonthNum()
    {
        return $this->_nextMonth->get("MM");
    }
    /**
     * @return int
     */
    public function getNextMonthYear()
    {
        return $this->_nextMonth->get("yyyy");
    }
    /**
     * @return String "MMMM yyyy"
     */
    public function getNextMonthAsDateString()
    {
        return $this->_nextMonth->get("MMMM yyyy");
    }
    /**
     * @return String
     */
    public function getPrevMonthName()
    {
        return $this->_prevMonth->get("MMMM");
    }
    /**
     * @return int
     */
    public function getPrevMonthNum()
    {
        return $this->_prevMonth->get("MM");
    }
    /**
     * @return int
     */
    public function getPrevMonthYear()
    {
        return $this->_prevMonth->get("yyyy");
    }
    /**
     * @return String
     */
    public function getPrevMonthAsDateString()
    {
        return $this->_prevMonth->get("MMMM yyyy");
    }
    /**
     * @return int
     */
    public function getNumWeeks()
    {
        return $this->_numWeeks;
    }
}