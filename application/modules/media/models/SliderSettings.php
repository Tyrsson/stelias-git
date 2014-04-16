<?php

/**
 * Slider
 *  
 * @author Joey
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Media_Model_SliderSettings extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'slidersettings';
	protected $_primary = 'name';
	protected $_sequence = false;
	
	public $effects = array('sliceDown' => 'Slice Down', 
							'sliceDownLeft' => 'Slice Down Left', 
							'sliceUp' => 'Slice Up', 
							'sliceUpLeft' => 'Slice Up Left', 
							'sliceUpDown' => 'Slice Up Down', 
							'sliceUpDownLeft' => 'Slice Up Down Left', 
							'fold' => 'Fold', 
							'fade' => 'Fade', 
							'random' => 'Random', 
							'slideInRight' => 'Slide In Right', 
							'slideInLeft' => 'Slide In Left', 
							'boxRandom' => 'Box Random', 
							'boxRain' => 'Box Rain', 
							'boxRainReverse' => 'Box Rain Reverse', 
							'boxRainGrow' => 'Box Rain Grow', 
							'boxRainGrowReverse' => 'Box Rain Grow Reverse'
							);
	
	
	public function fetchSettings($sliderName = 'default') {
		$query = $this->select()->from($this->_name, array('*'))->where('name = ?', $sliderName);
		return $this->fetchRow($query);
	}
	
}
