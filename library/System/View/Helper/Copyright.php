<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * Copyright helper
 *
 * @uses viewHelper System_View_Helper
 */
class System_View_Helper_Copyright extends System_View_Helper_Abstract {

	/**
	 * @var Zend_View_Interface
	 */
	public $view;
	protected $startYear = 2011;
	protected $endYear;
	/**
	 *
	 */
	public function copyright() {
		$this->endYear = date('Y');

		$this->html .= '<span class="copyright">
							<a>&copy;&nbsp;'. ( ($this->startYear == $this->endYear) ? $this->startYear : $this->startYear .'-'. $this->endYear) . '&nbsp;' . $this->appSettings->siteName . '&nbsp;|&nbsp;</a>
					    	<span>Site by <a href="http://dirextion.com">Dire<span class="dxX">X</span>tion</a></span>
		                </span>';

		return $this->html;
	}

	/**
	 * Sets the view field
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
