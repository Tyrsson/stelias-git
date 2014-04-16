<?php
/**
 *
 * @category   System
 * @package    Calendar
 * @subpackage Helper
 * @version
 */

class System_View_Helper_CalendarViewHelper extends Zend_View_Helper_Abstract
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

    /**
     * @param String $date (MMMM yyyy)
     */
    public function calendarViewHelper ($date = null, $locale = "en_US")
    {
        $this->setDate($date, $locale);
    }

    /**    
     *
     * @param Zend_Date $date
     */
    protected function initDateParams(Zend_Date $date)
    {
        $this->_monthNames = Zend_Locale::getTranslationList('Month', $this->_locale); //locale month list
        $this->_dayNames = Zend_Locale::getTranslationList('Day', $this->_locale); //locale day list
        $this->setMonthsInRange(); //set locale valid dates
        $this->_numMonthDays = $date->get(Zend_Date::MONTH_DAYS); //num days in locale month
        $this->setNextMonth($date); //set the next month
        $this->setPrevMonth($date); //set the previous month
        $this->_firstDayOfWeek = $date->get(Zend_Date::WEEKDAY_DIGIT); //first day of the curr month
        $this->_numWeeks = ceil(($this->getFirstDayOfWeek() + $this->getNumMonthDays()) / 7); //num weeks in curr month
    }
   
    /**
     *
     * @param int $startOffset
     * @param int $endOffset
     */
    public function setMonthsInRange($startOffset = -1, $endOffset = 12)
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
    public function setLocale($locale)
    {
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
                //defaults:
                $showPrevMonthLink=false;
                $showNextMonthLink=false;
                $selectBox=false;
                $selectBoxName="selectMonth";
                $selectBoxFormName="selectMonthForm";
               
                //params:
                if (is_array($arr)) {
                        extract( $arr );
                }
               
                //prev/next link in header display
        $pLink = $nLink = "";
        $pLinkClass = "id=\"prevMonth\" style=\"visibility: visible;\"";
                $nLinkClass = "id=\"nextMonth\" style=\"visibility: visible;\"";
               
        if ($showPrevMonthLink) {
            $t = $this->getPrevMonthAsDateString();
            if (! array_key_exists($t, $this->_validDates)) //check if the prev month in list of valid dates
                $pLinkClass = "id=\"prevMonth\" style=\"visibility: hidden;\"";
                        $pLink = "<a $pLinkClass href=\"?$selectBoxName=" . urlencode($t) . "\">&lt;&nbsp;$t</a>\n";
        }
        if ($showNextMonthLink) {
            $t = $this->getNextMonthAsDateString();
            if (! array_key_exists($t, $this->_validDates)) //check if the next month in list of valid dates
                $nLinkClass = "id=\"nextMonth\" style=\"visibility: hidden;\"";
                        $nLink = "<a $nLinkClass href=\"?$selectBoxName=" . urlencode($t) . "\">$t&nbsp;&gt;</a>\n";
        }
        //month in header display
        $headDate = $this->getDateAsString();
        if ($selectBox) {
            $headDate = "\n<form name=\"$selectBoxFormName\" method=\"get\">\n";
            $headDate .= $this->getValidDatesSelectBox(array('selectedDateStr'=>$this->getDateAsString(), 'selectBoxName'=>$selectBoxName));
            $headDate .= "</form>\n";
        }
                return "<div id=\"calendar_header\">$pLink&nbsp;$headDate&nbsp;$nLink</div>\n";
               
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
            if (is_array($arr)) {
                        extract( $arr );
                }

                $html = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$tableClass\">\n";
        $html .= "<tr class=\"weekdays\">\n";
               
        //days of the week display
        foreach ($this->_dayNames as $dayShort=>$dayFull) {
            $html .= "<td>$dayFull</td>\n";
        }
        $html .= "</tr>\n";
               
        //day numbers display
        $today = $this->_now->get("d");
        $nowDate = $this->_now->get("MMMM yyyy");
                $focusDate = $this->getDateAsString();
        $calDayNum = 1;
               
                //day numbers display loop
        for ($i = 0; $i < $this->getNumWeeks(); $i ++) {
            $html .= "<tr class=\"days\">";
            for ($j = 0; $j < 7; $j ++) {
                                $cellNum = ($i * 7 + $j);
                $class = "";
                               
                if ($showToday && $nowDate == $focusDate && $today == $calDayNum && $cellNum >= $this->getFirstDayOfWeek()) {
                    $class = "class = \"today\"";
                }
                $html .= "<td $class>";
                       
                if ($cellNum >= $this->getFirstDayOfWeek() && $cellNum < ($this->getNumMonthDays() + $this->getFirstDayOfWeek())) {
                    $html .= Zend_Locale_Format::toNumber($calDayNum, array('locale' => $this->_locale));
                    $calDayNum ++;
                }
                $html .= "</td>\n";
            }
            $html .= "</tr>\n";
        }
        $html .= "</table>\n";
                return $html;
        }
       
    /**
     * @return String
     */
    public function getCalendarHtml( $arr = NULL )
    {
        //defaults:
        $showToday=false;
                $showPrevMonthLink=false;
                $showNextMonthLink=false;
                $tableClass="calendar";
                $selectBox=false;
                $selectBoxName="selectMonth";
                $selectBoxFormName="selectMonthForm";
               
                //params:
        		if (is_array($arr)) {
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
        $html .= $this->getCalendarBodyHtml(array('showToday'=>$showToday, 'tableClass'=>$tableClass)); //returns a table
                $html .= "</div>\n</div>\n";
        return $html;
    }
   
    /**
     * @return String
     */
    public function getValidDatesSelectBox( $arr = NULL )
    {
                //defaults:
                $selectedDateStr=false;
                $selectBoxName="";
               
                //params:
        		if (is_array($arr)) {
                        extract( $arr );
                }
               
        		$html = "<select name=\"$selectBoxName\" onchange=\"submit();\">\n";
        	foreach ($this->_validDates as $option => $value) {
            $sel = "";
            if ($selectedDateStr && $selectedDateStr == $option) {
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
                try {
                        $this->_date = new Zend_Date($date, "MMMM yyyy", $this->_locale);
                } catch (Zend_Date_Exception $e) {
                        $this->_date = new Zend_Date(null, "MMMM yyyy", $this->_locale);
                }
                //date params
        $this->initDateParams($this->_date);
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