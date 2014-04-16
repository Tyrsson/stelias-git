<?php
/**
 *
 * @author jsmith
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * rightColumn helper
 *
 * @uses viewHelper Ttob_View_Helper
 */
class System_View_Helper_RightColumn extends System_View_Helper_Abstract {

	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;

	/**
	 */
	public function rightColumn() {
		$this->html .= '<div class="ui-social ui-widget-content ui-corner-all ui-helper-clearfix">
							<a href="http://birminghamboyschoir.com"><img class="ui-social-logo" src="/skins/'.SKIN_NAME.'/images/bbc-logo.png" /></a>
							<iframe src="http://www.facebook.com/plugins/likebox.php?id=125322940817269&amp;width=300&amp;connections=10&amp;stream=false&amp;header=false&amp;height=280" scrolling="no" frameborder="0" style="margin-left:1%; background-color: #ffffff; border:none; overflow:hidden; width:98%; height:280px;" allowTransparency="false"></iframe>
						</div>';
		return $this->html;
	}

	/**
	 * Sets the view field
	 *
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
