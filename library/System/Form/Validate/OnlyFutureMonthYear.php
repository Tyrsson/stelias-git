<?php

class System_Form_Validate_OnlyFutureMonthYear extends Zend_Validate_Abstract
{
    // Error wording constant(s)
    const PAST_DATE = 'past';

    protected $_monthField;

    public function __construct ($monthField)
    {
        $this->_monthField = $monthField;
    }

    protected $_messageTemplates = array(
            self::PAST_DATE => "'%value%' is set in the past"
    );

    /**
     * Returns true if month/year fields equals current date() month/year, or is
     * in the future
     * 
     * @param string $value            
     * @param ?? $context            
     * @return boolean
     *
     */
    public function isValid ($value, $context = '')
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        $this->_setValue($context[$this->_monthField] . '/' . $value);
        
        if ((int) $context[$this->_monthField] < $currentMonth &&
                 $value <= $currentYear) {
            $this->_error(self::PAST_DATE);
            return false;
        }
        return true;
    }
}
